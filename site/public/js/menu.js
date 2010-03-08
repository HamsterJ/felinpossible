<!--//--><![CDATA[//><!--
function opennav(submenu) {
	navRoot = document.getElementById("menu");
	for (i=0; i<navRoot.childNodes.length; i++) {
		node = navRoot.childNodes[i];
		if (node.nodeName=="LI" && node == submenu) {
			node.className=node.className ="open";
		}
		else {
			node.className="close";
		}
		//alert(node.nodeName + " "+ node.id + " : " + node.className)
	}
}

function closenav(submenu) {
	navRoot = document.getElementById("menu");
	for (i=0; i<navRoot.childNodes.length; i++) {
		node = navRoot.childNodes[i];
		if (node.nodeName=="LI" && node == submenu) {
			node.className=node.className ="close";
		}
		else {
			node.className="close";
		}
		//alert(node.nodeName + " "+ node.id + " : " + node.className)
	}
}

function test(submenu) {
	alert(submenu.id)
}

startList = function() {
		navRoot = document.getElementById("menu");
		if (navRoot) {
		  for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				node.onmouseover=function() {
					opennav(this);
				}
				/*node.onmouseout=function(){
					closenav(this);
				}*/
			if (node.className.length==0) node.className="close"
			}
		  }
		}
}
//--><!]]>