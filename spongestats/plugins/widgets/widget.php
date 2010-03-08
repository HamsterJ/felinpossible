<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:widget="http://www.netvibes.com/ns/">
<head>

<title>spongestats</title>
<meta name="author" content="SpongeStats" />
<meta name="description" content="Widget pour spongestats" />
<meta name="keywords" content="digg" />

<meta name="apiVersion" content="0.9" />
<meta name="inline" content="false" />
<meta name="autoRefresh" content="20" />
<meta name="autoResize" content="true" />

<meta name="debugMode" content="true" />

<link rel="stylesheet" type="text/css" href="http://www.netvibes.com/themes/uwa/style.css" />
<script type="text/javascript" src="http://www.netvibes.com/js/UWA/load.js.php?env=Standalone"></script>

<widget:preferences>
<preference name="day_visits" type="boolean" label="Afficher les visites du jour" defaultValue="true" />
<preference name="day_pages" type="boolean" label="Afficher les pages du jour" defaultValue="true" />
<preference name="browser" type="boolean" label="Afficher les navigateurs" defaultValue="true" />
<preference name="referer" type="boolean" label="Afficher les referers" defaultValue="true" />
<preference name="page_vue" type="boolean" label="Afficher les pages vues" defaultValue="true" />
</widget:preferences>

<style>
.sponge_red { color:#F00;}
.sponge_green { color:#339933;}
</style>
<script type="text/javascript">

var spongestats = {

  xml: false,

  items: [],

  query: null,

  offset: null,

  parse: function(xml) {
    this.updateTitle();
    this.items = [];
    if(xml) this.xml = xml;
    var entries = this.xml.getElementsByTagName("item");
	var base_url = this.xml.getElementsByTagName("base_url")[0].firstChild.nodeValue;
    var countItem = 0;
	var stats= new Array;
    for(var i=0; i < entries.length; i++) {
    var entry = entries[i];
    var title = entry.getElementsByTagName("title")[0].firstChild.nodeValue;
	var description = entry.getElementsByTagName("description")[0].firstChild.nodeValue;
	if (title=="day_pages" || title=="day_visits")
		{
		var stats =entry.getElementsByTagName("day")[0].firstChild.nodeValue;
		var evolution =Math.round(10*100* (entry.getElementsByTagName("day")[0].firstChild.nodeValue-entry.getElementsByTagName("eve")[0].firstChild.nodeValue)/(10*entry.getElementsByTagName("eve")[0].firstChild.nodeValue));
		var item = { title: title,description: description, stats: stats,evolution: evolution,base_url:base_url };
		this.items.push(item);
		countItem++;
		}
	else
		{
		var data =entry.getElementsByTagName(title);
		var item = { title: title,description: description, data: data,base_url:base_url };
		this.items.push(item);
		countItem++;
		}
   }
    this.show();
  },

  getContent: function() {
    if(this.items.length == 0) {
      return '<p>No spongestats news</p>';
    }
    if(widget.getValue('openlinks') == 'true') var target = 'target="_blank"'; else var target = '';
    var content = '<table>';
    var j = 0;
    for(var i = 0; i < this.items.length; i++) {
		var item = this.items[i];
		var displayTitle = item.title;
			if (displayTitle=="day_pages" || displayTitle=="day_visits")
				{
				var statsValue=item.stats;
				var statsEvolution=item.evolution;
				if (statsEvolution<0)
					{
					statsEvolutionClass='sponge_red';
					}
				else
					{
					statsEvolutionClass='sponge_green';
					}
				}
		 var displaydescription = item.description;

      // highlighting
      if(this.query != null) {
  		displayTitle = String.highlight(displayTitle, this.query);
  		displaydescription = String.highlight(displaydescription, this.query);
		Statsvalue = String.highlight(statsValue, this.query);
  	}
			if (widget.getValue(displayTitle)=='true')
					{
					content +='<tr>' +'<td style="padding:0 5px 5px 0;" align="center" valign="top">' +'<strong>' + displaydescription + '</strong><br /></span></div>' +'</td>'+'<td style="padding:0 5px 5px;" valign="top">';
					if (displayTitle=="day_pages" || displayTitle=="day_visits")
						{
						content += statsValue + ' <span class="'+statsEvolutionClass+'">(' + statsEvolution + '%)' + '</span>';
						}
					else
						{
						displayData=item.data;
						content += "<ol>";
						for(var k = 0; k < displayData.length; k++)
							{
									if (displayTitle=="referer")
										{
										content += "<li><a "+target+" href=\""+ displayData[k].firstChild.nodeValue+"\">"+displayData[k].firstChild.nodeValue+"</a></li>";
										}
									else if (displayTitle=="page_vue")
										{
										content += "<li><a "+target+" href=\""+ item.base_url+displayData[k].firstChild.nodeValue+"\">"+ displayData[k].firstChild.nodeValue+"</a></li>";
										}
									else
										{
										content += "<li>"+ displayData[k].firstChild.nodeValue+"</li>";
										}
							}
						}
				content += "</ol>";
				}


			content +=	'<br />' +
			  '</td>' +
			'</tr>';
      j++;
      if(j == widget.getValue('limit') ) break
    }
    content += '</table>';
    return content;
 widget.callback('onUpdateBody');

  },

  show: function() {
    if(this.offset == null) this.offset = widget.getValue('offset');
    if(!this.offset || this.offset < 0) { this.setOffset(0); }
    var openlinks = widget.getValue('openlinks');
    var limit = widget.getValue('limit');
    var content = this.getContent();
    widget.setBody(content);
    var pager = new UWA.Controls.Pager( { module: this, limit: limit, offset: this.offset, dataArray: this.items } );
    pager.onChange = function(newOffset) {
        this.module.setOffset(newOffset);
        this.module.show();
    }
    widget.addBody( pager.getContent() );
  },

  updateTitle: function() {
    var title = '<a href="http://spongestats.sourceforge.net/" target="_blank">' + 'spongestats</a>';
    var minimal = widget.getValue('minimal');
     var category = widget.getValue('category');
    if(this.query) {
      title += '<em> - ' + _("search") + ' : ' + this.query + '</em>';
    } else if(category && category != 'all') {
      title += '<em> - ' + _("category") + ' : ' + category + '</em>';
    }
    if(minimal && minimal != 0) {
      title += ' - ' + _("%s spongestatss minimum").s(minimal);
    }
    widget.setTitle(title);
  },

  setOffset: function(offset) {
    this.offset = offset;
    if(this.query == null) widget.setValue('offset', offset);
  },

  getRssUrl: function(offset) {
	 var rssUrl = '<?php $file_folder=$_SERVER['SCRIPT_NAME'];echo "http://".$_SERVER['HTTP_HOST'].str_replace(basename($file_folder),"",$file_folder)."/widget_xml.php"; ?>';
    return rssUrl;
  }

}

/* wigdet bindings */

widget.onLoad =  function() {
  spongestats.updateTitle();
  UWA.Data.getXml(spongestats.getRssUrl(), spongestats.parse.bind(spongestats) );
}

widget.onSearch = function(query, extended) {
    spongestats.offset = 0;
    spongestats.query = query;
    if(extended) {
      spongestats.extended = true;
      widget.onLoad();
    } else if(spongestats.extended) {
      spongestats.extended = null;
      widget.onLoad();
    } else {
      widget.reParse();
    }
}

widget.onResetSearch = function() {
    spongestats.offset = widget.getValue('offset');
    spongestats.query = null;
    if(spongestats.extended) {
      spongestats.extended = null;
      widget.onLoad();
    } else {
      widget.reParse();
    }
}

widget.onRefresh = function() {
  widget.setBody( _("Loading ...") );
  widget.onLoad();
  widget.getContent();
widget.callback('onUpdateBody');
}

widget.reParse = function() {
  if(spongestats.xml) spongestats.parse(); else widget.onLoad();
}

widget.rePaint = function() {
  if(spongestats.xml) spongestats.show(); else widget.onLoad();
}



</script>

</head>
<body>

</body>
</html>