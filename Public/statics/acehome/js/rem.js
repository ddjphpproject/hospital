
//designWidth:璁捐绋跨殑瀹為檯瀹藉害鍊硷紝闇€瑕佹牴鎹疄闄呰缃�
//maxWidth:鍒朵綔绋跨殑鏈€澶у搴﹀€硷紝闇€瑕佹牴鎹疄闄呰缃�
//杩欐js鐨勬渶鍚庨潰鏈変袱涓弬鏁拌寰楄璁剧疆锛屼竴涓负璁捐绋垮疄闄呭搴︼紝涓€涓负鍒朵綔绋挎渶澶у搴︼紝渚嬪璁捐绋夸负750锛屾渶澶у搴︿负750锛屽垯涓�(750,750)
;(function(designWidth, maxWidth) {
	var doc = document,
	win = window,
	docEl = doc.documentElement,
	remStyle = document.createElement("style"),
	tid;

	function refreshRem() {
		var width = docEl.getBoundingClientRect().width;
		maxWidth = maxWidth || 540;
		width>maxWidth && (width=maxWidth);
		var rem = width * 100 / designWidth;
		remStyle.innerHTML = 'html{font-size:' + rem + 'px;}';
	}

	if (docEl.firstElementChild) {
		docEl.firstElementChild.appendChild(remStyle);
	} else {
		var wrap = doc.createElement("div");
		wrap.appendChild(remStyle);
		doc.write(wrap.innerHTML);
		wrap = null;
	}
	//瑕佺瓑 wiewport 璁剧疆濂藉悗鎵嶈兘鎵ц refreshRem锛屼笉鐒� refreshRem 浼氭墽琛�2娆★紱
	refreshRem();

	win.addEventListener("resize", function() {
		clearTimeout(tid); //闃叉鎵ц涓ゆ
		tid = setTimeout(refreshRem, 300);
	}, false);

	win.addEventListener("pageshow", function(e) {
		if (e.persisted) { // 娴忚鍣ㄥ悗閫€鐨勬椂鍊欓噸鏂拌绠�
			clearTimeout(tid);
			tid = setTimeout(refreshRem, 300);
		}
	}, false);

	if (doc.readyState === "complete") {
		doc.body.style.fontSize = "16px";
	} else {
		doc.addEventListener("DOMContentLoaded", function(e) {
			doc.body.style.fontSize = "16px";
		}, false);
	}
})(750, 750);