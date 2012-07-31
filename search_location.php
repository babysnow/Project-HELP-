<?php session_start() ?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="project/style.css" />

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>UIUC Local Search API + Maps demo</title>
    <link href="http://www.google.com/uds/css/gsearch.css" rel="stylesheet" type="text/css"/>
    <link href="./places.css" rel="stylesheet" type="text/css"/>

    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxQ82LsCgTSsdpNEnBsExtoeJv4cdBSUkiLH6ntmAr_5O4EfjDwOa0oZBQ" type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[

    // Our global state
    var gLocalSearch;
    var gMap;
    var gInfoWindow;
    var gSelectedResults = [];
    var gCurrentResults = [];
    var gSearchForm;

    // Create our "tiny" marker icon
    var gYellowIcon = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_yellow.png",
      new google.maps.Size(12, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));
    var gRedIcon = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_red.png",
      new google.maps.Size(12, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));
    var gSmallShadow = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_shadow.png",
      new google.maps.Size(22, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));

     // Set up the map and the local searcher.
    function OnLoad() {

      // Initialize the map with default UI.
      gMap = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(40.099, -88.227),
        zoom: 13,
        mapTypeId: 'roadmap'
      });
      // Create one InfoWindow to open when a marker is clicked.
      gInfoWindow = new google.maps.InfoWindow;
      google.maps.event.addListener(gInfoWindow, 'closeclick', function() {
        unselectMarkers();
      });

      // Initialize the local searcher
      gLocalSearch = new GlocalSearch();
      gLocalSearch.setSearchCompleteCallback(null, OnLocalSearch);
      var location = getUrlQuery();
      if (location != "undefined, uiuc") {
        gLocalSearch.setCenterPoint(gMap.getCenter());
        gLocalSearch.execute(location);
      }
    }

    function unselectMarkers() {
      for (var i = 0; i < gCurrentResults.length; i++) {
        gCurrentResults[i].unselect();
      }
    }

    function doSearch() {
      var input = document.getElementById("queryInput").value;
      var query = getQuery(input);
      gLocalSearch.setCenterPoint(gMap.getCenter());
      gLocalSearch.execute(query);
    }

    function getQuery(str) {
      var input = new String(str);
      if (input == "accy" || input == "ACCY") {
        ret = "accounting, uiuc";
      }
      else if (input == "arc" || input == "ARC") {
        ret = "arc center";
      }
      else if (input == "krannert" || input == "kcpa" || input == "KCPA") {
        ret = "krannert center, uiuc";
      }
      else if (input == "i-card" || input == "icard" || input == "ICARD") {
        ret = "illini bookstore, uiuc";
      }
      else if (input == "isss" || input == "ISSS") {
        ret = "international student affairs, uiuc";
      }
      else if (input == "sc" || input == "SC") {
        ret = "siebel center, uiuc";
      }
      else if (input == "tb" || input == "TB") {
        ret = "transportation building, uiuc";
      }
      else {
        ret = input + ", uiuc";
      }
      return ret;
    }

    function getUrlParams()    
    {
      var args=new Object();   
      var query=location.search.substring(1);
      var pairs=query.split("&");
      for(var i=0; i<pairs.length; i++)   
      {   
        var pos=pairs[i].indexOf('=');
        if(pos==-1)   continue;
        var argname=pairs[i].substring(0,pos);
        var value=pairs[i].substring(pos+1);
        args[argname]=unescape(value);
      }
      return args;
    }

    function getUrlQuery()
    {
      var args = new Object();
      args = getUrlParams();
      var location = args["location"];
      var ret = getQuery(location);
      return ret;
    }

    // Called when Local Search results are returned, we clear the old
    // results and load the new ones.
    function OnLocalSearch() {
      if (!gLocalSearch.results) return;
      var searchWell = document.getElementById("searchwell");

      // Clear the map and the old search well
      searchWell.innerHTML = "";
      for (var i = 0; i < gCurrentResults.length; i++) {
        gCurrentResults[i].marker().setMap(null);
      }
      // Close the infowindow
      gInfoWindow.close();

      gCurrentResults = [];
      for (var i = 0; i < gLocalSearch.results.length; i++) {
        gCurrentResults.push(new LocalResult(gLocalSearch.results[i]));
      }

      var attribution = gLocalSearch.getAttribution();
      if (attribution) {
        document.getElementById("searchwell").appendChild(attribution);
      }

      // Move the map to the first result
      var first = gLocalSearch.results[0];
      gMap.setCenter(new google.maps.LatLng(parseFloat(first.lat),
                                            parseFloat(first.lng)));

    }

    // Cancel the form submission, executing an AJAX Search API search.
    function CaptureForm(searchForm) {
      gLocalSearch.execute(searchForm.input.value);
      return false;
    }



    // A class representing a single Local Search result returned by the
    // Google AJAX Search API.
    function LocalResult(result) {
      var me = this;
      me.result_ = result;
      me.resultNode_ = me.node();
      me.marker_ = me.marker();
      google.maps.event.addDomListener(me.resultNode_, 'mouseover', function() {
        // Highlight the marker and result icon when the result is
        // mouseovered.  Do not remove any other highlighting at this time.
        me.highlight(true);
      });
      google.maps.event.addDomListener(me.resultNode_, 'mouseout', function() {
        // Remove highlighting unless this marker is selected (the info
        // window is open).
        if (!me.selected_) me.highlight(false);
      });
      google.maps.event.addDomListener(me.resultNode_, 'click', function() {
        me.select();
      });
      document.getElementById("searchwell").appendChild(me.resultNode_);
    }

    LocalResult.prototype.node = function() {
      if (this.resultNode_) return this.resultNode_;
      return this.html();
    };

    // Returns the GMap marker for this result, creating it with the given
    // icon if it has not already been created.
    LocalResult.prototype.marker = function() {
      var me = this;
      if (me.marker_) return me.marker_;
      var marker = me.marker_ = new google.maps.Marker({
        position: new google.maps.LatLng(parseFloat(me.result_.lat),
                                         parseFloat(me.result_.lng)),
        icon: gYellowIcon, shadow: gSmallShadow, map: gMap});
      google.maps.event.addListener(marker, "click", function() {
        me.select();
      });
      return marker;
    };

    // Unselect any selected markers and then highlight this result and
    // display the info window on it.
    LocalResult.prototype.select = function() {
      unselectMarkers();
      this.selected_ = true;
      this.highlight(true);
      gInfoWindow.setContent(this.html(true));
      gInfoWindow.open(gMap, this.marker());
    };

    LocalResult.prototype.isSelected = function() {
      return this.selected_;
    };

    // Remove any highlighting on this result.
    LocalResult.prototype.unselect = function() {
      this.selected_ = false;
      this.highlight(false);
    };

    // Returns the HTML we display for a result before it has been "saved"
    LocalResult.prototype.html = function() {
      var me = this;
      var container = document.createElement("div");
      container.className = "unselected";
      container.appendChild(me.result_.html.cloneNode(true));
      return container;
    }

    LocalResult.prototype.highlight = function(highlight) {
      this.marker().setOptions({icon: highlight ? gRedIcon : gYellowIcon});
      this.node().className = "unselected" + (highlight ? " red" : "");
    }

    GSearch.setOnLoadCallback(OnLoad);
    //]]>
    </script>
  </head>
  <body style="font-family: Arial, sans-serif; font-size: small;">
    <div id="title"  style="font-family:arial;color:white;font-size:30px;background:#f6822b;right:20px">Username: <?php echo $_SESSION['username'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <div>
      <img src="project/assets/uiuc_banner.jpg" alt="University of Ilinois" width="120" height="28" style="position: absolute; top:12px; left: 430px"/>
    </div>
    <div><a href='project/index2.php' style="font-family:arial;color:white;font-size:20px;background:#f6822b; position: absolute; top: 14px; left: 561px; text-decoration:none"> CS411 Project </a>
    </div>
    <div style="position: absolute; top: 14px; right: 20px; "> <a href= "mailto:dhe6@illinois.edu" style="font-family:arial;color:white;font-size:20px;background:#f6822b; text-decoration:none">Contact</a>
    </div>

  <div style="position:relative; left:100px">
    <p>Perform a local search on the map below:</p>
    <div style="width: 680px;">
      <div style="margin-bottom: 5px;">
        <div>
          <input type="text" id="queryInput" value="" style="width: 250px;"/>
          <input type="button" value="Find" onclick="doSearch()"/>
        </div>
      </div>
      <div style="position: absolute; left: 700px;">
        <div id="searchwell"></div>
      </div>
      <div id="map" style="height: 500px; border: 1px solid #979797;"></div>
    </div>
  </div>
  </body>
</html>


