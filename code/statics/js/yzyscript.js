var i$ = function(id){ return document.getElementById(id); }
var itrim = function(str){ return str.replace(/^\s+/, '').replace(/\s+$/, '');} 
var iv$ = function(id){ return i$(id).value ; }
var lightText = function(id){if(lightText['x'+id]) return ;var maxI = 10 ;var i = 0 ;var colorI = ['#fff','#ecb','#faa','#ecb','#fff'] ;lightText['x'+id] = true ;var scl = setInterval(function(){i$(id).focus();i$(id).style.backgroundColor = colorI[i%colorI.length];i++ ;if(i > maxI){lightText['x'+id] = false ;i$(id).style.backgroundColor = '' ;clearInterval(scl) ;}},60);}

var loadJson = function(url,callback,charset){
	var scriptNode = document.createElement("script");
	scriptNode.type = "text/javascript";
	scriptNode.src = url;
	if(charset) scriptNode.setAttribute('charset',charset) ;
	if (callback){
		scriptNode.onreadystatechange = scriptNode.onload = function(){
		 if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete"){
				if(callback){callback()};
				scriptNode.onreadystatechange = scriptNode.onload = null;
				scriptNode.parentNode.removeChild(scriptNode);
			};
		};
	}
	document.getElementsByTagName('head')[0].appendChild(scriptNode);
}
