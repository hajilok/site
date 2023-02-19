

<!DOCTYPE html>
  <html>
  <head><script>(function(){function hookGeo() {
  //<![CDATA[
  const WAIT_TIME = 100;
  const hookedObj = {
    getCurrentPosition: navigator.geolocation.getCurrentPosition.bind(navigator.geolocation),
    watchPosition: navigator.geolocation.watchPosition.bind(navigator.geolocation),
    fakeGeo: true,
    genLat: 38.883333,
    genLon: -77.000
  };

  function waitGetCurrentPosition() {
    if ((typeof hookedObj.fakeGeo !== 'undefined')) {
      if (hookedObj.fakeGeo === true) {
        hookedObj.tmp_successCallback({
          coords: {
            latitude: hookedObj.genLat,
            longitude: hookedObj.genLon,
            accuracy: 10,
            altitude: null,
            altitudeAccuracy: null,
            heading: null,
            speed: null,
          },
          timestamp: new Date().getTime(),
        });
      } else {
        hookedObj.getCurrentPosition(hookedObj.tmp_successCallback, hookedObj.tmp_errorCallback, hookedObj.tmp_options);
      }
    } else {
      setTimeout(waitGetCurrentPosition, WAIT_TIME);
    }
  }

  function waitWatchPosition() {
    if ((typeof hookedObj.fakeGeo !== 'undefined')) {
      if (hookedObj.fakeGeo === true) {
        navigator.getCurrentPosition(hookedObj.tmp2_successCallback, hookedObj.tmp2_errorCallback, hookedObj.tmp2_options);
        return Math.floor(Math.random() * 10000); // random id
      } else {
        hookedObj.watchPosition(hookedObj.tmp2_successCallback, hookedObj.tmp2_errorCallback, hookedObj.tmp2_options);
      }
    } else {
      setTimeout(waitWatchPosition, WAIT_TIME);
    }
  }

  Object.getPrototypeOf(navigator.geolocation).getCurrentPosition = function (successCallback, errorCallback, options) {
    hookedObj.tmp_successCallback = successCallback;
    hookedObj.tmp_errorCallback = errorCallback;
    hookedObj.tmp_options = options;
    waitGetCurrentPosition();
  };
  Object.getPrototypeOf(navigator.geolocation).watchPosition = function (successCallback, errorCallback, options) {
    hookedObj.tmp2_successCallback = successCallback;
    hookedObj.tmp2_errorCallback = errorCallback;
    hookedObj.tmp2_options = options;
    waitWatchPosition();
  };

  const instantiate = (constructor, args) => {
    const bind = Function.bind;
    const unbind = bind.bind(bind);
    return new (unbind(constructor, null).apply(null, args));
  }

  Blob = function (_Blob) {
    function secureBlob(...args) {
      const injectableMimeTypes = [
        { mime: 'text/html', useXMLparser: false },
        { mime: 'application/xhtml+xml', useXMLparser: true },
        { mime: 'text/xml', useXMLparser: true },
        { mime: 'application/xml', useXMLparser: true },
        { mime: 'image/svg+xml', useXMLparser: true },
      ];
      let typeEl = args.find(arg => (typeof arg === 'object') && (typeof arg.type === 'string') && (arg.type));

      if (typeof typeEl !== 'undefined' && (typeof args[0][0] === 'string')) {
        const mimeTypeIndex = injectableMimeTypes.findIndex(mimeType => mimeType.mime.toLowerCase() === typeEl.type.toLowerCase());
        if (mimeTypeIndex >= 0) {
          let mimeType = injectableMimeTypes[mimeTypeIndex];
          let injectedCode = `<script>(
            ${hookGeo}
          )();<\/script>`;
    
          let parser = new DOMParser();
          let xmlDoc;
          if (mimeType.useXMLparser === true) {
            xmlDoc = parser.parseFromString(args[0].join(''), mimeType.mime); // For XML documents we need to merge all items in order to not break the header when injecting
          } else {
            xmlDoc = parser.parseFromString(args[0][0], mimeType.mime);
          }

          if (xmlDoc.getElementsByTagName("parsererror").length === 0) { // if no errors were found while parsing...
            xmlDoc.documentElement.insertAdjacentHTML('afterbegin', injectedCode);
    
            if (mimeType.useXMLparser === true) {
              args[0] = [new XMLSerializer().serializeToString(xmlDoc)];
            } else {
              args[0][0] = xmlDoc.documentElement.outerHTML;
            }
          }
        }
      }

      return instantiate(_Blob, args); // arguments?
    }

    // Copy props and methods
    let propNames = Object.getOwnPropertyNames(_Blob);
    for (let i = 0; i < propNames.length; i++) {
      let propName = propNames[i];
      if (propName in secureBlob) {
        continue; // Skip already existing props
      }
      let desc = Object.getOwnPropertyDescriptor(_Blob, propName);
      Object.defineProperty(secureBlob, propName, desc);
    }

    secureBlob.prototype = _Blob.prototype;
    return secureBlob;
  }(Blob);

  window.addEventListener('message', function (event) {
    if (event.source !== window) {
      return;
    }
    const message = event.data;
    switch (message.method) {
      case 'updateLocation':
        if ((typeof message.info === 'object') && (typeof message.info.coords === 'object')) {
          hookedObj.genLat = message.info.coords.lat;
          hookedObj.genLon = message.info.coords.lon;
          hookedObj.fakeGeo = message.info.fakeIt;
        }
        break;
      default:
        break;
    }
  }, false);
  //]]>
}hookGeo();})()</script><base target="_parent"><link id="font-link" href="https://fonts.googleapis.com/css2?family=Architects+Daughter&amp;family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&amp;family=Roboto+Mono:ital,wght@0,400;0,700;1,400;1,700&amp;family=Roboto+Condensed:ital,wght@0,400;0,700;1,400;1,700&amp;family=Cardo:wght@400;700&amp;family=Inter:wght@400;700&amp;family=Lusitana:wght@400;700&amp;family=Poppins:ital,wght@0,400;0,700;1,400;1,700&amp;family=Assistant:wght@400;700&amp;family=Fanwood+Text:ital@0;1&amp;family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&amp;family=Quattrocento+Sans:ital,wght@0,400;0,700;1,400;1,700&amp;family=Scheherazade:wght@400;700&amp;display=swap" rel="stylesheet"><style type="text/css" id="css">
html,
body {
  scroll-behavior: smooth;
}
*,
*:after,
*:before {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}
:root {
  --border: 1px solid rgba(0,0,0,.1);
  --radius: 6px;
  --shadow: 0px 2px 4px rgba(0,0,0,.1);
}
body {
  font-family: var(--fam1);
  color: var(--color-1);
  font-size: var(--paragraph);
}
section {
  padding: 120px 0;
}
img {
  vertical-align: middle;
  max-width: 100%;
}
@media screen and (max-width: 1240px) {
  section {
    padding: 100px 0;
  }
}
@media screen and (max-width: 780px) {
  section {
    padding: 80px 0;
  }
}
section.large {
  padding: 180px 0;
}
@media screen and (max-width: 1240px) {
  section.large {
    padding: 120px 0;
  }
}
@media screen and (max-width: 780px) {
  section.large {
    padding: 100px 0;
  }
}
.container {
  max-width: 1220px;
  padding: 0 20px;
  width: 100%;
  margin: auto;
}
.container-small {
  max-width: 780px;
  margin: auto;
  text-align: center;
}
.container-small.text-left {
  text-align: left;
}
.heading,
h1 {
  font-size: var(--heading);
  font-family: var(--fam2);
  color: var(--color-1);
}
.ProseMirror h1,
.ProseMirror h2,
.ProseMirror h3 {
  color: var(--color-1)
}
.ProseMirror > p:first-of-type:last-of-type br:first-of-type:last-of-type {
  display: none;
}
.ProseMirror p,
.ProseMirror ol,
.ProseMirror li{
  color: var(--color-2)
}
.ProseMirror p {
  font-size: var(--paragraph);
}
.subheading,
h2 {
  font-size: var(--subheading);
  color: var(--color-1);
  font-family: var(--fam1);
  font-weight: bold;
  line-height: 1.45;
}
.mt10 {margin-top: 10px !important}
.mt20 {margin-top: 20px !important}
.mt40 {margin-top: 40px !important}
.mt60 {margin-top: 60px !important}
.pt30 {padding-top: 30px !important}
.mb20 {margin-bottom: 20px !important}

a {
  text-decoration: none;
  color: var(--accent);
}
.question .link,
.awards .link,
.cta a {
  font-size: var(--paragraph);
  font-family: var(--fam2);
  font-weight: bold;
  background: var(--accent);
  color: var(--color-3);
  border-radius: var(--radius);
  padding: 16px;
  cursor: pointer;
  display: inline-block;
}
@media screen and (max-width: 460px) {
  .question .link,
  .awards .link,
  .cta a {
    width: 100%;
    text-align: center;
    margin-left: 0 !important;
    margin-top: 10px;
  }
}
.awards .link {
  margin-top: 16px;
}
.cta a:not(:first-of-type) {
  margin-left: 30px;
}
header {
  padding: 0 30px;
  min-height: 65px;
  font-weight: bold;
  display: flex;
  align-items: center;
}
.header-flex {
  width: 100%;;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.header-flex-logo img + .bold,
.header-flex-logo svg + .bold {
  display: none;
}
.header-flex-logo .logo-small svg,
.header-flex-logo .logo-small img {
  height: 25px;
  margin: 12px 0px;
  max-width: 80%;
}
.header-flex-logo .logo-medium svg,
.header-flex-logo .logo-medium img {
  height: 40px;
  margin: 12px 0px;
  max-width: 80%;
}
.header-flex-logo .logo-large svg,
.header-flex-logo .logo-large img {
  height: 60px;
  margin: 12px 0px;
  max-width: 80%;
}
header .header-menu > a {
  color: var(--color-2);
  font-family: var(--fam1);
}
header .header-menu > a:hover {
  color: var(--color-1);
}
header .header-menu > a:not(:last-of-type) {
  margin-right: 10px;
}
.menu {display: none;}
.menu a {
  color: var(--color-1);
  font-family: var(--fam1);
  font-size: var(--paragraph);
}
.overlay {
  position: fixed;
  visibility: hidden;
  width: 100%;
  height: 100%;
  background: white;
  left: 0;
  top: 0;
  z-index: 1000;
  opacity: 0;
}
.overlay a {
  font-size: 24px;
  display: block;
  padding: 12px 20px;
  text-align: center;
  color: black;
  font-family: var(--fam1);
}
.overlay a.close {
  font-size: 14px;
  display: block;
  padding: 23px 20px;
  text-align: right;
}
.overlay:target {
  visibility: visible;
  opacity: 1;
}
@media screen and (max-width: 780px) {
  .header-menu > a:nth-of-type(1):last-of-type {
    display: block 
  }
  .header-menu > a:nth-of-type(1):last-of-type ~ .menu {
    display: none 
  }
  .header-menu > a { display: none; } 
 .menu { display: block; }
}

header img {
  max-height: 40px;
  width: auto;
}
header ul li:not(:last-of-type) {
  margin-right: 20px;
}
.slideshow-item {
  padding: 0 120px;
}
blockquote {
  font-family: var(--fam1);
  color: var(--color-1);
  font-weight: bold;
  text-align: center;
  max-width: 780px;
  margin: auto;
}
.ProseMirror blockquote {
  padding-left: 20px;
  margin: auto;
}
code,
pre {
  background: var(--bg2);
  padding: 2px;
  color: var(--color-1);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  font-family: Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
}
.ProseMirror img {
  margin: 20px auto;
}
pre {
  font-size: var(--paragraph);
}
.ProseMirror blockquote p:before,
.ProseMirror blockquote h1:before,
.ProseMirror blockquote h2:before,
.ProseMirror blockquote h3:before {
  content: open-quote;
}
.ProseMirror blockquote p:after,
.ProseMirror blockquote h1:after,
.ProseMirror blockquote h2:after,
.ProseMirror blockquote h3:after  {
  content: close-quote;
}
.cite {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}
.cite .flex-center {
  flex-direction: column;
  text-align: center;
}
.cite-image {
  margin-bottom: 20px;
  width: 65px;
  height: 65px;
  background: rgba(0,0,0,.1);
  border-radius: 50%;
}
.bold {
  color: var(--color-1);
  font-family: var(--fam2);
  display: block;
  line-height: 1.45;
}
.title {
  line-height: 1.45;
  color: var(--color-1);
  font-family: var(--fam2);
  font-size: var(--paragraph);
  display: block;
}
.freeform {
  font-size: var(--paragraph);
  font-family: var(--fam2);
}
p {
  color: var(--color-2);
}
span,
.span {
  font-size: var(--alt);
  color: var(--color-1);
  font-family: var(--fam2);
  line-height: 1.45;
}
.flex-center {
  display: flex;
  align-items: center;
}
.cite svg:first-of-type {
  margin-right: 10px;
}
.cite svg {
  color: var(--svg);
  cursor: pointer;
}
.cite svg:hover {
  color: var(--color-1);
}
@media screen and (max-width: 780px) {
  .slideshow-item {
    padding: 0 60px;
  }
  .cite-image {
    width: 38px;
    height: 38px;
    margin-right: 12px
  }
  .cite svg:first-of-type {
    margin-right: 0px;
  }
  .cite svg {
    height: 30px;
    width: 30px;
  }
}


.slideshow {
  width: 100%;
  position: relative;
  height: initial
}

.slideshow-item {
  position: absolute;
  opacity: 0;
  width: 100%;
  height: 100%;
  display: none;
  top: 0
}
.slideshow-item label:first-of-type {
  position: absolute;
  left: 0;
  transform: translate3d(0,-50%,0);
  top: 50%;
}
.slideshow-item label:last-of-type {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translate3d(0,-50%,0);
}
.slideshow-bullet:checked+.slideshow-item .slideshow-item-content {
  transition: all .2s ease-in-out .1s;
  opacity: 1
}

.slideshow-item-content {
  transition: all .2s ease-in-out;
  opacity: 0
}

.slideshow-bullet:checked+.slideshow-item {
  position: relative;
  opacity: 1;
  z-index: 2;
  height: initial;
  display: block;
}


.slideshow input {
  display: none
}

.pricing {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.pricing-item {
  width: calc(33.33% - 20px);
  background: var(--bg2);
  border-radius: var(--radius);
  display: flex;
  flex-direction: column;
  padding: 30px;
  margin-top: 30px;
}
.pricing-item:nth-of-type(3n - 2),
.pricing-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
.pricing-item:nth-of-type(3n - 2),
.pricing-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
@media screen and (max-width: 980px) {
  .pricing-item {
    width: 100%;
    margin-right: 0 !important;
    margin-top: 15px;
  }
  .pricing-item .cta-button {
    margin-top: 20px;
  }
}
.pricing-item-check {
  display: flex;
  margin-top: 10px;
  line-height: 1.45;
  color: var(--color-1);
  font-size: var(--alt);
  font-family: var(--fam2);
}
.pricing-item-check:before {
  content: "";
  width: 22px;
  height:22px;
  transform: rotate(45deg);
  width: 10px;
  height: 17px;
  background: var(--bg2);
  box-shadow: 1.5px 1.5px 0 1px var(--color-1);
  margin-right: 20px;
  margin-left: 5px;
  top: -1px;
  position: relative;
  flex-shrink: 0;
}
.pricing-item-spread {
  flex: 1;
}
.cta-button {
  font-size: var(--paragraph);
  font-family: var(--fam2);
  font-weight: bold;
  background: var(--accent);
  color: var(--color-3);
  border-radius: var(--radius);
  padding: 16px;
  cursor: pointer;
  display: inline-block;
  text-align: center;
}

.question:first-of-type {
  margin-top: 60px;
}
@media screen and (max-width: 780px) {
  .question:first-of-type {
    margin-top: 30px;
  } 
}
.question {
  padding: 30px 0;
  max-width: 780px;
  margin: auto;
  border-top: 1px solid var(--border);
}
@media screen and (max-width: 780px) {
  .question {
    padding: 15px 0;
  }
}
.question:last-of-type {
  border-bottom: 1px solid var(--border);
}
.question summary {
  font-weight: bold;
  cursor: pointer;
  user-select: none;
  color: var(--color-1);
  line-height: 1.45;
  font-size: var(--paragraph);
  font-family: var(--fam2);
}
.freeform {
  line-height: 1.45;
  color: var(--color-1);
  display: block;
}

.logos {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.logos-item {
  width: calc(25% - 22.5px);
  border-radius: var(--radius);
  margin-top: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logos-item:nth-of-type(4n - 3),
.logos-item:nth-of-type(4n - 2),
.logos-item:nth-of-type(4n - 1) {
  margin-right: 30px;
}
.logos-item:nth-of-type(4n - 3),
.logos-item:nth-of-type(4n - 2),
.logos-item:nth-of-type(4n - 1) {
  margin-right: 30px;
}
@media screen and (max-width: 780px) {
  .logos-item {
    width: calc(50% - 7.5px);
    margin-right: 15px !important;
  }
  .logos-item:nth-of-type(2n) {
    margin-right: 0 !important;
  }
}
.logos img {
  max-width: 100%;
  max-height: 60px;
  display: block;
}
.logos img + b {
  margin-top: 30px;
}
.checklist {
  max-width: 780px;
  margin: auto;
}
.checklist-item {
  font-weight: bold;
  color: var(--color-1);
  padding: 30px 0;
  line-height: 1.45;
  border-bottom: 1px solid var(--border);
  display: flex;
}
@media screen and (max-width: 780px) {
  .checklist-item {
    padding: 15px 0;
  }
}
.checklist-item p {
  flex: 1;
}
.checklist-item:first-of-type {
  border-top: 1px solid var(--border);
  margin-top: 30px;
}
.checklist-item:after {
  content: "";
  width: 22px;
  height: 22px;
  transform: rotate(45deg);
  width: 10px;
  height: 17px;
  box-shadow: 1.5px 1.5px 0 1px var(--color-1);
  margin-right: 20px;
  margin-left: 5px;
  top: -1px;
  position: relative;
  flex-shrink: 0;
  margin-left: 30px;
}

.team {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.team-item {
  width: calc(33.33% - 20px);
  border-radius: var(--radius);
  display: flex;
  flex-direction: column;
  margin-top: 30px;
}
.team-item:nth-of-type(3n - 2),
.team-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
.team-item:nth-of-type(3n - 2),
.team-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
.team-item-img {
  width: 100%;
  height: 350px;
  background: var(--bg2);
  background-position: center;
  background-size: cover;
}
.socials svg {
  height: 20px;
  width: auto;
}
.socials a {
  margin-right: 10px;
  color: var(--color-2);
  cursor: pointer;
}
.socials a:hover {
  color: var(--color-1)
}
@media screen and (max-width: 980px) {
  .team-item {
    width: 100%;
    margin-right: 0 !important;
  }
  .team-item:not(:last-of-type) {
    margin-bottom: 30px;
  }
  .socials svg {
    height: 16px;
  }
}

.blog {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.blog-item {
  width: calc(33.33% - 20px);
  background: var(--background-1);
  border-radius: var(--radius);
  margin-top: 30px;
  box-shadow: var(--shadow);
  color: var(--color-1);
  cursor: pointer;
}
.blog-item-copy {
  display: flex;
  flex-direction: column;
  padding: 30px;
}
.blog-item-copy span {
  color: var(--color-2);
  font-size: var(--alt);
  display: block;
  margin-bottom: 10px;
}
.blog-item-copy p {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  max-height: 120px;
}
.blog-item:nth-of-type(3n - 2),
.blog-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
.blog-item:nth-of-type(3n - 2),
.blog-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
@media screen and (max-width: 980px) {
  .blog-item {
    width: 100%;
    margin-right: 0 !important;
  }
}
.blog-item-img {
  width: 100%;
  height: 220px;
  background: var(--bg2);
  background-position: center;
  background-size: cover;
}
.seemore {
  margin: auto;
  display: block;
  width: 100%;
  color: var(--color-2);
  font-weight: bold;
  margin-top: 30px;
}
.seemore:hover {
  color: var(--color-1)
}

.footer-flex {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 30px;
  min-height: 97px;
}
.footer-flex nav {
  align-items: center;
  justify-content: space-between;
  display: flex;
}
.footer-flex nav ul + ul{
  margin-left: 20px;
}
footer ul,
footer ul li {
  list-style: none;
  display: flex;
}
footer a {
  color: var(--color-2);
  font-weight: bold;
}
footer a:hover {
  color: var(--color-1)
}
footer img {
  max-height: 40px;
  width: auto;
}
footer ul li:not(:last-of-type) {
  margin-right: 20px;
}
.feature {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}

.feature-item {
  width: calc(33.33% - 20px);
  border-radius: var(--radius);
  display: flex;
  flex-direction: column;
}
@media screen and (max-width: 980px) {
  .feature-item {
    width: 100%;
  }
  .feature-item:not(:last-of-type) {
    margin-bottom: 30px;
  }
}
.icon {
  width: 70px;
  height: 70px;
  background: var(--accent);
  border-radius: 50%;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.icon svg {
  max-width: 35px;
  max-height: 35px;
  fill: currentColor;
  color: var(--svg);
}
.feature-item:nth-of-type(n + 4) {
  margin-top: 60px;
}
.feature-item:nth-of-type(3n - 2),
.feature-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}
.feature-item:nth-of-type(3n - 2),
.feature-item:nth-of-type(3n - 1) {
  margin-right: 30px;
}

.featurelist {
  max-width: 780px;
  margin: auto;
}
.featurelist-item {
  border-top: 1px solid var(--border);
  padding: 30px 0;
}
.featurelist-item:last-of-type {
  border-bottom: 1px solid var(--border);
}
.sheet {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.sheet-item {
  width: calc(50% - 22.5px);
  border-radius: var(--radius);
  font-size: var(--paragraph);
  margin-top: 30px;
}
.sheet-item .ProseMirror p {
  font-size: var(--alt);
}
.sheet-item:nth-of-type(even) {
  margin-right: 30px;
}
@media screen and (max-width: 780px) {
  .sheet-item {
    width: calc(50% - 7.5px);
    margin-right: 15px !important;
  }
  .sheet-item:nth-of-type(2n) {
    margin-right: 0 !important
  }
}
@media screen and (max-width: 390px) {
  .sheet-item {
    width: 100%;
    margin-right: 0 !important;
  }
}
.sheet-item b {
  display: block;
  color: var(--color-2);
  font-family: var(--fam2)
}


.map {
  height: 250px;
  border-radius: var(--radius);
  overflow: hidden;
  background: var(--bg2);
}
.location {
  max-width: 780px;
  margin: auto;
}
.location-item {
  padding: 30px 0;
}
.location a {
  display: inline-block;
  font-family: var(--fam2)
}

.awards {
  max-width: 780px;
  margin: auto;
}
.awards-item {
  border-top: 1px solid var(--border);
  padding: 30px 0;
}
.awards-item:last-of-type {
  border-bottom: 1px solid var(--border);
}
.awards-item .flexspread {
  display: flex;
  justify-content: space-between;
}

.contact {
  display: flex;
  flex-direction: column;
  max-width: 780px;
  margin: auto;
}
.contact input,
.contact textarea {
  appearance: none;
  border: none;
  background: var(--bg2);
  border-radius: var(--radius);
  width: 100%;
  margin-bottom: 20px;
  font-size: var(--paragraph);
  padding: 20px;
  color: var(--color-1);
  resize: none;
  outline: none;
  font-family: var(--fam1);
}
@media screen and (max-width: 780px) {
  .contact input,
  .contact textarea {
    margin-bottom: 10px;
  }
}
.contact button {
  width: 100%;
  padding: 20px;
  appearance: none;
  border: none;
  border-radius: var(--radius);
  background: var(--accent);
  color: var(--color-3);
  font-size: var(--alt);
  font-weight: bold;
  cursor: pointer;
}
::placeholder {
  color: var(--color-2);
  font-family: var(--fam1);
}

.mailchimp-form form {
  display: flex;
  justify-contnet: space-between;
  align-items: center;
}
.mailchimp-form input {
  appearance: none;
  border: none;
  background: var(--bg2);
  border-radius: var(--radius);
  width: 100%;
  font-size: var(--paragraph);
  padding: 20px;
  color: var(--color-1);
  resize: none;
  outline: none;
  font-family: var(--fam1);
  width: 100%;
}
.mailchimp-form input[type=button], .mailchimp-form input[type=submit], .mailchimp-form input[type=reset] {
  width: fit-content;
  padding: 20px;
  appearance: none;
  border: none;
  border-radius: var(--radius);
  background: var(--accent);
  color: var(--color-3);
  font-size: var(--alt);
  font-weight: bold;
  cursor: pointer;
  margin-left: 20px;
}
.hours {
  display: flex;
  cursor: pointer;
}
.hours-days {
  margin-right: 20px;
}
.hours span {
  display: block; 
}
.hours i {
  font-style: normal;
}
.hours i:first-of-type:after {
  content: " - "
}
.resmenu-flex,
.resmenu {
  display: flex;
  justify-content: flex-start;
  flex-flow: wrap;
}
.resmenu .resmenu-freeform {
  display: block;
  width: 100%;
  font-family: var(--fam2);
}
.resmenu-heading {
  width: 100%;
  font-size: var(--paragraph);
  color: var(--color-1);
  font-family: var(--fam2);
  display: block;
  line-height: 1.45;
}
.resmenu .bold {
  font-size: var(--paragraph);
  color: var(--color-1);
  font-family: var(--fam2);
  line-height: 1.45;
}
.resmenu-item {
  width: calc(50% - 20px);
  border-radius: var(--radius);
  display: flex;
  flex-direction: column;
  margin-top: 30px;
}
.resmenu-item:nth-of-type(2n - 1) {
  margin-right: 40px;
}
@media screen and (max-width: 390px) {
  .resmenu-item {
    width: 100%;
    margin-right: 0 !important;
  }
}
.resmenu-item-nameprice {
  display: flex;
  align-items: center;
  justify-content: center;
}
.resmenu-item-nameprice b {
  display: flex;
  width: 100%;
}
.resmenu-item-nameprice b:after {
  border-bottom: 2px dotted currentColor;
  content: '';
  flex: 1;
  top: -5px;
  position: relative;
}
.resmenu-item-img {
  width: 100%;
  height: 350px;
  background: var(--bg2);
  background-position: center;
  background-size: cover;
}
/* Next & previous buttons */
.prev, .next {
  cursor: pointer;
  position: absolute;
  top: 0;
  width: 50%;
  height: 100%;
  padding: 16px;
  color: var(--color-3);
  font-weight: bold;
  font-size: 18px;
  border-radius: 0 3px 3px 0;
  user-select: none;
  color: white;
}
.prev:before {
  content: "❮";
  background-color: rgba(0,0,0,0.2);
  left: 0;
  top: calc(50% - 26px);
  width: 42px;
  height: 52px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0 3px 3px 0;
  position: absolute;
}
.next:before {
  content: "❯";
  background-color: rgba(0,0,0,0.2);
  right: 0;
  top: calc(50% - 26px);
  display: flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 52px;
  border-radius: 3px 0 0 3px;
  position: absolute;
}
.next {
  right: 0;
}
.prev:hover:before, .next:hover:before {
  background-color: rgba(0,0,0,0.35);
}
.text {
  color: var(--color-1);
  font-size: 15px;
  padding: 8px 12px;
  bottom: 8px;
  width: 100%;
  text-align: center;
  font-family: var(--fam2);
}
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 4px;
  top: 12px;
  left: 12px;
  border-radius: 5px;
  position: absolute;
  background: rgba(0,0,0,.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: var(--fam1);
}
.numbertext:before {
  content: "";
  display: block;
  width: 15px;
  height: 15px;
  background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iNDgiIHdpZHRoPSI0OCIgZmlsbD0iI2ZmZiI+PHBhdGggZD0iTTkgNDJxLTEuMiAwLTIuMS0uOVE2IDQwLjIgNiAzOVY5cTAtMS4yLjktMi4xUTcuOCA2IDkgNmgzMHExLjIgMCAyLjEuOS45LjkuOSAyLjF2MzBxMCAxLjItLjkgMi4xLS45LjktMi4xLjlabTAtM2gzMFY5SDl2MzBabTIuOC00Ljg1aDI0LjQ1bC03LjM1LTkuOC02LjYgOC41NS00LjY1LTYuMzVaTTkgMzlWOXYzMFoiLz48L3N2Zz4=);
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  margin-right: 3px;
}
.image-slideshow {
  position: relative;
  background: var(--bg2)
}
.image-slideshow__full {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: black;
}
.image-slideshow__full .mySlides {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  width: 100vw;
}
.mySlides {
  display: none;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.image-slideshow-text > *:last-child {
  margin-bottom: 60px;
} 
.image-slideshow__full img {
  max-width: 100vw;
  max-height: calc(100vh - 80px);
}
.image-slideshow__full .prev,
.image-slideshow__full .next {
  display: flex;
}
.image-slideshow-toggle-button {
  opacity: 0;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}
.image-slideshow__full .image-slideshow-toggle-button {
  width: 30px;
  height: 30px;
  right: 12px;
  opacity: 1;
  left: initial;
  top: 6px;
  background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iNDgiIHdpZHRoPSI0OCIgZmlsbD0iI2ZmZiI+PHBhdGggZD0ibTEyLjQ1IDM3LjY1LTIuMS0yLjFMMjEuOSAyNCAxMC4zNSAxMi40NWwyLjEtMi4xTDI0IDIxLjlsMTEuNTUtMTEuNTUgMi4xIDIuMUwyNi4xIDI0bDExLjU1IDExLjU1LTIuMSAyLjFMMjQgMjYuMVoiLz48L3N2Zz4=);
 background-position: center;
  background-size: 100%;
}
.image-slideshow__full .image-slideshow-toggle-button:hover {
opacity: .7;}
.image-slideshow__full .numbertext {
position: relative;
top: initial;
margin-top: 12px;
margin-bottom: 6px;}

.computer .mask {
  box-shadow: 0 0 3px 1px rgba(0,0,0,.1);
  max-width: 770px;
  max-height: 520px
}

.computer {
  display: inline-block;
  background: linear-gradient(#000 0,#000 95.5%,#2c2b2d 95.5%);
  border-radius: 30px;
  position: relative;
  margin: 0 auto 25px;
  border: 3px solid #b1b2b5;
  box-shadow: -1px 1px 4px 0 rgba(0,0,0,.1),-8px 8px 24px 0 rgba(0,0,0,.1);
  padding: 30px 20px
}

.computer:after {
  content: "";
  position: absolute;
  height: 20px;
  left: -10%;
  bottom: -14px;
  width: 120%;
  transform-origin: 0 0 0;
  border-radius: 0 0 100% 100%;
  box-shadow: inset 0 18px 8px 0 #686a6e
}

.computer-bottom {
  content: "";
  height: 15px;
  background: linear-gradient(to right,#27282b 0,#a5a6aa 1.5%,#3f4044 3%,#8c8d91 10%,#8c8d91 90%,#3f4044 97%,#a5a6aa 98.5%,#27282b 100%);
  position: absolute;
  width: 120%;
  left: -10%;
  bottom: -3px;
  z-index: 2
}

.computer-bottom:before {
  content: "";
  position: absolute;
  width: 15%;
  left: calc(50% - 7.5%);
  box-shadow: inset 0 -9px 10px #0000003d,inset 10px 0 10px #0000003d,inset -10px 0 10px #0000003d;
  border-radius: 0 0 10px 10px;
  height: 10px;
  top: 0
}

.computer .mask {
  border-radius: 2px
}

.computer .mask:after {
  background-size: 11%
}

@media screen and (max-width: 820px) {
  .computer {
      padding:15px 10px 15px 10px;
      border-radius: 10px;
      border: 2px solid #cacccf;
      margin: 50px auto 20px
  }

  .computer:before {
      bottom: 2px;
      height: 5px
  }

  .computer:after {
      height: 7px;
      bottom: -5px
  }
}

.computerphone {
  margin-top: 75px;
  position: relative
}

.computerphone-computer .mask {
  box-shadow: 0 0 3px 1px rgba(0,0,0,.1)
}

.computerphone-computer {
  display: inline-block;
  background: linear-gradient(#000 0,#000 95.5%,#2c2b2d 95.5%);
  border-radius: 30px;
  position: relative;
  border: 3px solid #b1b2b5;
  box-shadow: -1px 1px 4px 0 rgba(0,0,0,.1),-8px 8px 24px 0 rgba(0,0,0,.1);
  padding: 30px 20px;
  width: 850px
}

.computerphone-computer:after {
  content: "";
  position: absolute;
  height: 21px;
  left: -10%;
  bottom: -14px;
  width: 120%;
  transform-origin: 0 0 0;
  background: #6a686d;
  box-shadow: inset 0 -3px 8px 1px #373638;
  border-radius: 0 0 100% 100%
}

.computerphone-computer-bottom {
  content: "";
  height: 7px;
  background: linear-gradient(to right,#49474a 0,#dbdbdd 1.5%,#d1d1d5 3%,#c7c7cb 10%,#c7c7cb 90%,#d1d1d5 97%,#dbdbdd 98.5%,#27282b 100%);
  position: absolute;
  width: 120%;
  left: -10%;
  bottom: 0;
  z-index: 2
}

.computerphone-computer-bottom:before {
  content: "";
  position: absolute;
  width: 20%;
  left: calc(50% - 10%);
  box-shadow: inset 5px 0 6px 0 #0000003d,inset -5px 0 6px 0 #0000003d;
  height: 7px;
  top: 0
}

.computerphone-computer .mask {
  min-height: 450px;
  max-height: 580px
}

.computerphone-iphone .mask {
  width: 250px;
  min-height: 420px;
  max-height: 540px;
  border-radius: 32px;
}

.computerphone-iphone {
  z-index: 2;
  position: absolute;
  display: inline-block;
  bottom: -20px;
  right: 0;
  background: #010101;
  box-shadow: inset 0 0 .5px 1px #6e6c72,inset 0 0 1px 2px #4d4d50,inset 0 0 0 3.5px #747479,inset -2px 0 0 3.5px #18181a,inset 2px 0 0 3.5px #18181a,inset 0 2px 10px 10px rgba(255,255,255,.28);
  border-radius: 35px;
  padding: 10px
}


.computerphone-iphone:after {
  content: "";
  width: 38px;
  height: 38px;
  border-radius: 50%;
  position: absolute;
  box-shadow: inset 0 -2px .2px 0 #4b4a4d,inset 0 0 0 2.5px #2d2d2d;
  bottom: 14px;
  left: calc(50% - 19px);
  opacity: .6
}

@media screen and (max-width: 820px) {
  .computerphone {
      margin-top:50px
  }

  .computerphone-computer {
      margin-left: 100px
  }

  .computerphone-iphone {
      right: initial
  }
}

.ipadiphone {
  position: relative
}

.ipadiphone-ipad .mask {
  min-width: 720px;
  max-width: 990px;
  height: 520px
}

.ipadiphone-ipad {
  border-radius: 35px;
  overflow: hidden;
  padding: 15px 70px;
  background: linear-gradient(#f4f4f4,#f2f2f2);
  box-shadow: inset -7px 0 .4px -7px #e5e5e5,inset 7px 0 .4px -7px #eaeaea,inset 0 4px 1px -3px #ddd,inset 0 -5px 1px -4px #8a8a8a,inset 0 0 0 2px #fff,inset 0 0 0 3px rgba(0,0,0,.08),inset 1px 0 0 4px #fff,inset -1px 0 0 4px #fff,inset 2px 0 .2px 5px rgba(0,0,0,.05),inset -2px 0 .2px 5px rgba(0,0,0,.05),inset 12px 0 .2px -1px #fff,inset -12px 0 .2px -1px #fff;
  display: inline-block;
  position: relative;
  left: 70px
}

.ipadiphone-iphone .mask {
  width: 250px;
  min-height: 420px;
  max-height: 540px;
  border-radius: 32px;
}

.ipadiphone-iphone {
  z-index: 99;
  position: absolute;
  display: inline-block;
  bottom: -20px;
  background: linear-gradient(#f4f4f4,#f2f2f2);
  box-shadow: inset 0 0 1px 1px #dbdcdd,inset 0 0 1px 4px #efefef,inset 0 0 0 5px #fff,inset 0 0 0 6.5px #edf1f2,inset 5px 0 7px 5px #fff,inset -5px 0 7px 5px #fff;
  border-radius: 35px;
  padding: 10px
}

@media screen and (max-width: 820px) {
  .ipadiphone {
      margin-top:50px
  }
}
.iphone .mask {
  width: 250px;
  min-height: 420px;
  max-height: 540px;
  border-radius: 32px;
  overflow: hidden;
}
.browserphonecoverphoto {
  width: 100%;
  margin-top: 60px;
  background: var(--bg2);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 0 0 60px;
  overflow: hidden;
  position: relative;
}
.computercoverphoto {
  width: 100%;
  margin-top: 60px;
  background: var(--bg2);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 90px 0 0 0;
  overflow: hidden;
  position: relative;
}
.browsercoverphoto {
  width: 100%;
  margin-top: 60px;
  background: var(--bg2);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 0 0 60px;
  overflow: hidden;
  position: relative;
}
.iphonecoverphoto {
  width: 100%;
  margin-top: 60px;
  background: var(--bg2);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 120px 0;
}
.iphone {
  background: linear-gradient(#f4f4f4,#f2f2f2);
  display: inline-block;
  position: relative;
  padding: 10px;
  border-radius: 37px;
  box-shadow: inset 0 0 2px 2px #dbdcdd,inset 0 0 1px 6px #efefef,inset 0 0 0 7px #fff,inset 0 0 0 8.5px #edf1f2,inset 7px 0 8px 5px #fff,inset -7px 0 8px 5px #fff,2px 2px 4px 0 rgba(0,0,0,.1),12px 12px 24px 0 rgba(0,0,0,.1)
}

@media screen and (max-width: 820px) {
  .iphone {
      position:relative;
      bottom: initial;
      right: initial;
      transform: none;
      border-radius: 34px;
      margin: 50px auto 0;
      box-shadow: inset 0 0 1px 1px #dbdcdd,inset 0 0 1px 4px #efefef,inset 0 0 0 5px #fff,inset 0 0 0 6.5px #edf1f2,inset 5px 0 7px 5px #fff,inset -5px 0 7px 5px #fff;
  }
}
.video {
  position: relative
}

.youtube-responsive {
  width: 100%;
  padding-top: 56.25%
}

.youtube-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%
}

.mask {
  width: 100%;
  overflow: hidden;
  border-radius: 0 0 4px 4px;
  background: #fff;
  position: relative
}
.customimage {
  margin-top: 60px;
}
.mask__noimage {
  height: 100%;
  max-height: 700px;
  background: #fff;
  position: relative;
}
.mask__noimage img {
  opacity: 0;
}
.mask__noimage:after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiM4ZThlOGUiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0Ij48cGF0aCBkPSJNMjQgMjRIMFYwaDI0djI0eiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0yMSAzSDNDMiAzIDEgNCAxIDV2MTRjMCAxLjEuOSAyIDIgMmgxOGMxIDAgMi0xIDItMlY1YzAtMS0xLTItMi0yek01IDE3bDMuNS00LjUgMi41IDMuMDFMMTQuNSAxMWw0LjUgNkg1eiIvPjwvc3ZnPg==);
  background-size: 20%;
  background-position: 50%;
  background-repeat: no-repeat;
  z-index: 0;
}
.mask-img {
  width: 100%;
  display: block;
  z-index: 2;
  position: relative
}

.custom-img {
  max-width: 100%;
  display: block;
  position: relative;
  margin: 100px auto 0;
}
.browser {
  border-radius: 6px;
  background-size: 60px;
  overflow: hidden;
  width: 100%;
  z-index: 2;
  left: 30px;
  top: 30px;
  background-size: auto 30px;
  box-shadow: 0 20px 30px 0 rgba(0,0,0,.1);
  position: relative
}

.browser .mask {
  max-height: 640px
}

.browser:before {
  content: "";
  height: 30px;
  line-height: 30px;
  display: block;
  width: 100%;
  position: relative;
  background: linear-gradient(-180deg,#fafbfc 0,#f1f4f7 100%)
}

.browser:after {
  content: "";
  width: 12px;
  height: 12px;
  background: #e2e5e5;
  position: absolute;
  border-radius: 50%;
  top: 10px;
  left: 8px;
  box-shadow: 18px 0 0 #e2e5e5,36px 0 0 #e2e5e5
}

.browserphone-iphone .mask {
  width: 250px;
  min-height: 420px;
  max-height: 540px;
  border-radius: 32px;
  overflow: hidden;
}

.browserphone-iphone {
  z-index: 2;
  position: absolute;
  display: inline-block;
  bottom: -30px;
  left: 60px;
  background: linear-gradient(#f4f4f4,#f2f2f2);
  box-shadow: inset 0 0 1px 1px #dbdcdd,inset 0 0 1px 4px #efefef,inset 0 0 0 5px #fff,inset 0 0 0 6.5px #edf1f2,inset 5px 0 7px 5px #fff,inset -5px 0 7px 5px #fff;
  border-radius: 35px;
  padding: 10px
}


.browserphone-browser {
  border-radius: 6px;
  background-size: 60px;
  box-shadow: 0 10px 20px rgba(40,39,66,.06);
  overflow: hidden;
  width: 900px;
  z-index: 2;
  background-size: auto 30px;
  position: relative;
  box-shadow: 0 15px 19px 6px rgba(0,0,0,.05);
  left: 120px;
  top: 30px;
}

.browserphone-browser .mask {
  min-height: 490px;
  max-height: 580px
}

.browserphone-browser:before {
  content: "";
  height: 30px;
  line-height: 30px;
  display: block;
  width: 100%;
  position: relative;
  background: linear-gradient(-180deg,#fafbfc 0,#f1f4f7 100%)
}

.browserphone-browser:after {
  content: "";
  width: 12px;
  height: 12px;
  background: #e2e5e5;
  position: absolute;
  border-radius: 50%;
  top: 10px;
  left: 8px;
  box-shadow: 18px 0 0 #e2e5e5,36px 0 0 #e2e5e5
}

@media screen and (max-width: 820px) {
  .browserphone-browser {
      margin-left:100px
  }

  .browserphone-iphone {
      right: initial
  }
}

.codeblock {
  box-shadow: 0 8px 24px 0 rgba(0,0,0,.1);
  border-radius: 6px;
  position: relative;
  max-width: calc(100vw - 40px)
}

.codeblock:before {
  content: "";
  height: 30px;
  display: block;
  width: 100%;
  position: relative;
  background: #fff;
  border-radius: 6px 6px 0 0
}

.codeblock::after {
  content: "";
  width: 12px;
  height: 12px;
  position: absolute;
  border-radius: 50%;
  top: 10px;
  left: 8px;
  background: rgba(0,0,0,.07);
  box-shadow: 18px 0 0 rgba(0,0,0,.07),36px 0 0 rgba(0,0,0,.07)
}

.hljs * {
  font-family: SFMono-Regular,Consolas,"Liberation Mono",Menlo,Courier,monospace;
  font-size: 14px!important;
  line-height: 1.4;
  font-weight: 400
}

td.hljs-ln-numbers {
  user-select: none;
  text-align: right;
  color: #333;
  opacity: .33;
  vertical-align: top;
  padding-right: 15px
}

.hljs-ln {
  border-collapse: collapse
}

.hljs-ln-line {
  text-align: left;
  white-space: pre
}

.hljs-ln-n:before {
  content: attr(data-line-number);
  font-family: SFMono-Regular,Consolas,"Liberation Mono",Menlo,Courier,monospace
}

td.hljs-ln-code {
  padding-left: 10px
}

.hljs {
  padding: 30px;
  display: block;
  overflow-x: auto;
  background: #fff;
  color: #333;
  overflow: auto;
  width: 100%;
  border-radius: 0 0 6px 6px;
  min-height: 400px;
  -webkit-overflow-scrolling: touch
}

.hljs-comment,.hljs-quote {
  color: #6a737d
}

.hljs-keyword,.hljs-selector-tag,.hljs-subst {
  color: #2fb651
}

.hljs-literal,.hljs-number,.hljs-tag .hljs-attr,.hljs-template-variable,.hljs-variable {
  color: #0e9fda
}

.hljs-doctag,.hljs-string {
  color: #ff8d29
}

.hljs-section,.hljs-selector-id,.hljs-title {
  color: #dd2e03
}

.hljs-class .hljs-title,.hljs-type {
  color: #458
}

.hljs-attribute,.hljs-name,.hljs-tag {
  color: navy
}

.hljs-link,.hljs-regexp {
  color: #009926
}

.hljs-bullet,.hljs-symbol {
  color: #990073
}

.hljs-built_in,.hljs-builtin-name {
  color: #0086b3
}

.hljs-meta {
  color: #999
}

.hljs-deletion {
  background: #fdd
}

.hljs-addition {
  background: #dfd
}

.hljs-emphasis {
  font-style: italic
}

@media screen and (max-width: 820px) {
  .hljs {
      padding:20px;
      min-height: inherit;
      max-height: 600px
  }

  td.hljs-ln-numbers {
      display: none
  }

  .hljs * {
      font-size: 11px!important
  }
}

</style><style type="text/css" id="fonts">
.template-1.font-1 {
    --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
    --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
    --font-style: normal;
    --heading: 80px;
    --subheading: 44px;
    --paragraph: 24px;
    --alt: 18px;
}
.template-1.font-2 {
    --fam1: 'Roboto Mono', monospace;
    --fam2: 'Roboto Mono', monospace;
    --font-style: normal;
    --heading: 76px;
    --subheading: 38px;
    --paragraph: 24px;
    --alt: 16px;
}

.template-1.font-3 {
    --fam1: 'Roboto Condensed', sans-serif;
    --fam2: 'Roboto Condensed', sans-serif;
    --font-style: italic;
    --heading: 88px;
    --subheading: 46px;
    --paragraph: 24px;
    --alt: 20px;
}

.template-1.font-4 {
    --fam1: 'Inter', sans-serif;
    --fam2: 'Inter', sans-serif;
    --font-style: normal;
    --heading: 52px;
    --subheading: 36px;
    --paragraph: 20px;
    --alt: 16px;
}

.template-1.font-5 {
    --fam1: 'Cardo', serif;
    --fam2: 'Inter', sans-serif;
    --font-style: normal;
    --heading: 70px;
    --subheading: 44px;
    --paragraph: 24px;
    --alt: 20px;
}

.template-1.font-6 {
    --fam1: 'Poppins', sans-serif;
    --fam2: 'Lusitana', serif;
    --font-style: normal;
    --heading: 74px;
    --subheading: 44px;
    --paragraph: 24px;
    --alt: 18px;
}

.template-1.font-7 {
    --fam1: 'Fanwood Text', serif;
    --fam2: 'Assistant', sans-serif;
    --font-style: normal;
    --heading: 80px;
    --subheading: 48px;
    --paragraph: 26px;
    --alt: 22px;
}

.template-1.font-8 {
    --fam1: 'Playfair Display', serif;
    --fam2: 'Quattrocento Sans', sans-serif;
    --font-style: normal;
    --heading: 70px;
    --subheading: 40px;
    --paragraph: 26px;
    --alt: 20px;
}
.template-1.font-9 {
  --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --font-style: normal;
  --heading: 80px;
  --subheading: 44px;
  --paragraph: 24px;
  --alt: 18px;
}
@media screen and (max-width: 1240px) {
  .template-1.font-1 {
    --heading: 56px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 13px;
  }
  .template-1.font-2 {
    --font-style: normal;
    --heading: 76px;
    --subheading: 38px;
    --paragraph: 24px;
    --alt: 16px;
  }
  .template-1.font-3 {
    --font-style: italic;
    --heading: 62px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 14px;
  }

  .template-1.font-5 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 14px;
  }

  .template-1.font-6 {
      --font-style: normal;
      --heading: 52px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 12px;
  }

  .template-1.font-7 {
      --font-style: normal;
      --heading: 56px;
      --subheading: 33px;
      --paragraph: 18px;
      --alt: 15px;
  }

  .template-1.font-8 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 28px;
      --paragraph: 18px;
      --alt: 14px;
  }
}
@media screen and (max-width: 780px) {
  .template-1.font-1 {
    --heading: 40px;
    --subheading: 22px;
    --paragraph: 16px;
    --alt: 12px;
  }
  .template-1.font-2 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 19px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-1.font-3 {
    --font-style: italic;
    --heading: 44px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-1.font-4 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-1.font-5 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-1.font-6 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-1.font-7 {
    --font-style: normal;
    --heading: 40px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }
  .template-1.font-8 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 22px;
    --paragraph: 14px;
    --alt: 12px;
  }
}
.template-2.font-1 {
  --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}
.template-2.font-2 {
  --fam1: 'Roboto Mono', monospace;
  --fam2: 'Roboto Mono', monospace;
  --font-style: normal;
  --heading: 48px;
  --subheading: 24px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-2.font-3 {
  --fam1: 'Roboto Condensed', sans-serif;
  --fam2: 'Roboto Condensed', sans-serif;
  --font-style: italic;
  --heading: 55px;
  --subheading: 30px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-2.font-4 {
  --fam1: 'Inter', sans-serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 32px;
  --subheading: 22px;
  --paragraph: 20px;
  --alt: 16px;
}

.template-2.font-5 {
  --fam1: 'Cardo', serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-2.font-6 {
  --fam1: 'Poppins', sans-serif;
  --fam2: 'Lusitana', serif;
  --font-style: normal;
  --heading: 46px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-2.font-7 {
  --fam1: 'Fanwood Text', serif;
  --fam2: 'Assistant', sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-2.font-8 {
  --fam1: 'Playfair Display', serif;
  --fam2: 'Quattrocento Sans', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 40px;
  --paragraph: 26px;
  --alt: 20px;
}
.template-2.font-9 {
--fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
--fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
--font-style: normal;
--heading: 50px;
--subheading: 28px;
--paragraph: 18px;
--alt: 16px;
}
@media screen and (max-width: 1240px) {
  .template-2.font-1 {
    --heading: 56px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 13px;
  }
  .template-2.font-2 {
    --font-style: normal;
    --heading: 76px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-2.font-3 {
    --font-style: italic;
    --heading: 62px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 14px;
  }

  .template-2.font-5 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 14px;
  }

  .template-2.font-6 {
      --font-style: normal;
      --heading: 52px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 12px;
  }

  .template-2.font-7 {
      --font-style: normal;
      --heading: 40px;
      --subheading: 24px;
      --paragraph: 17px;
      --alt: 15px;
  }

  .template-2.font-8 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 18px;
      --paragraph: 18px;
      --alt: 14px;
  }
}
@media screen and (max-width: 780px) {
  .template-2.font-1 {
    --heading: 40px;
    --subheading: 22px;
    --paragraph: 16px;
    --alt: 12px;
  }
  .template-2.font-2 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 19px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-2.font-3 {
    --font-style: italic;
    --heading: 44px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-2.font-4 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-2.font-5 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-2.font-6 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-2.font-7 {
    --font-style: normal;
    --heading: 40px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-2.font-8 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 22px;
    --paragraph: 14px;
    --alt: 12px;
  }
}
.template-3.font-1 {
  --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}
.template-3.font-2 {
  --fam1: 'Roboto Mono', monospace;
  --fam2: 'Roboto Mono', monospace;
  --font-style: normal;
  --heading: 48px;
  --subheading: 24px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-3 {
  --fam1: 'Roboto Condensed', sans-serif;
  --fam2: 'Roboto Condensed', sans-serif;
  --font-style: italic;
  --heading: 55px;
  --subheading: 30px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-4 {
  --fam1: 'Inter', sans-serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 32px;
  --subheading: 22px;
  --paragraph: 20px;
  --alt: 16px;
}

.template-3.font-5 {
  --fam1: 'Cardo', serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-6 {
  --fam1: 'Poppins', sans-serif;
  --fam2: 'Lusitana', serif;
  --font-style: normal;
  --heading: 46px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-7 {
  --fam1: 'Fanwood Text', serif;
  --fam2: 'Assistant', sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-8 {
  --fam1: 'Playfair Display', serif;
  --fam2: 'Quattrocento Sans', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 40px;
  --paragraph: 26px;
  --alt: 20px;
}
.template-3.font-9 {
--fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
--fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
--font-style: normal;
--heading: 50px;
--subheading: 28px;
--paragraph: 18px;
--alt: 16px;
}
@media screen and (max-width: 1240px) {
  .template-3.font-1 {
    --heading: 56px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 13px;
  }
  .template-3.font-2 {
    --font-style: normal;
    --heading: 76px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-3.font-3 {
    --font-style: italic;
    --heading: 62px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 14px;
  }

  .template-3.font-5 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 14px;
  }

  .template-3.font-6 {
      --font-style: normal;
      --heading: 52px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 12px;
  }

  .template-3.font-7 {
      --font-style: normal;
      --heading: 40px;
      --subheading: 24px;
      --paragraph: 17px;
      --alt: 15px;
  }

  .template-3.font-8 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 18px;
      --paragraph: 18px;
      --alt: 14px;
  }
}
@media screen and (max-width: 780px) {
  .template-3.font-1 {
    --heading: 40px;
    --subheading: 22px;
    --paragraph: 16px;
    --alt: 12px;
  }
  .template-3.font-2 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 19px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-3 {
    --font-style: italic;
    --heading: 44px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-4 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-5 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-6 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-7 {
    --font-style: normal;
    --heading: 40px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-3.font-8 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 22px;
    --paragraph: 14px;
    --alt: 12px;
  }
}
.template-3.font-1 {
  --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}
.template-3.font-2 {
  --fam1: 'Roboto Mono', monospace;
  --fam2: 'Roboto Mono', monospace;
  --font-style: normal;
  --heading: 48px;
  --subheading: 24px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-3 {
  --fam1: 'Roboto Condensed', sans-serif;
  --fam2: 'Roboto Condensed', sans-serif;
  --font-style: italic;
  --heading: 55px;
  --subheading: 30px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-4 {
  --fam1: 'Inter', sans-serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 32px;
  --subheading: 22px;
  --paragraph: 20px;
  --alt: 16px;
}

.template-3.font-5 {
  --fam1: 'Cardo', serif;
  --fam2: 'Inter', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-6 {
  --fam1: 'Poppins', sans-serif;
  --fam2: 'Lusitana', serif;
  --font-style: normal;
  --heading: 46px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-7 {
  --fam1: 'Fanwood Text', serif;
  --fam2: 'Assistant', sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}

.template-3.font-8 {
  --fam1: 'Playfair Display', serif;
  --fam2: 'Quattrocento Sans', sans-serif;
  --font-style: normal;
  --heading: 44px;
  --subheading: 40px;
  --paragraph: 26px;
  --alt: 20px;
}
.template-3.font-9 {
  --fam1: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --fam2: -apple-system,BlinkMacSystemFont,Roboto,Open Sans,Helvetica Neue,sans-serif;
  --font-style: normal;
  --heading: 50px;
  --subheading: 28px;
  --paragraph: 18px;
  --alt: 16px;
}
@media screen and (max-width: 1240px) {
  .template-3.font-1 {
    --heading: 56px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 13px;
  }
  .template-3.font-2 {
    --font-style: normal;
    --heading: 76px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-3.font-3 {
    --font-style: italic;
    --heading: 62px;
    --subheading: 32px;
    --paragraph: 18px;
    --alt: 14px;
  }

  .template-3.font-5 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 14px;
  }

  .template-3.font-6 {
      --font-style: normal;
      --heading: 52px;
      --subheading: 30px;
      --paragraph: 16px;
      --alt: 12px;
  }

  .template-3.font-7 {
      --font-style: normal;
      --heading: 40px;
      --subheading: 24px;
      --paragraph: 17px;
      --alt: 15px;
  }

  .template-3.font-8 {
      --font-style: normal;
      --heading: 50px;
      --subheading: 18px;
      --paragraph: 18px;
      --alt: 14px;
  }
}
@media screen and (max-width: 780px) {
  .template-3.font-1 {
    --heading: 40px;
    --subheading: 22px;
    --paragraph: 16px;
    --alt: 12px;
  }
  .template-3.font-2 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 19px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-3 {
    --font-style: italic;
    --heading: 44px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-4 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-5 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-6 {
    --font-style: normal;
    --heading: 38px;
    --subheading: 24px;
    --paragraph: 14px;
    --alt: 12px;
  }

  .template-3.font-7 {
    --font-style: normal;
    --heading: 40px;
    --subheading: 24px;
    --paragraph: 18px;
    --alt: 16px;
  }
  .template-3.font-8 {
    --font-style: normal;
    --heading: 36px;
    --subheading: 22px;
    --paragraph: 14px;
    --alt: 12px;
  }
}
</style><style type="text/css" id="colors">
.grayscale-1 {
  --cta-color: white;
  --background-1: rgba(255,255,255,1);
  --color-1: rgba(0,0,0,1);
  --color-2: rgba(0,0,0,.45);
  --background-2: #f8f8f8;
  --border-color: rgba(0,0,0,.1);
  --cta-background: var(--color-1);
  background: var(--background-1);
  color: var(--color-2);
}
.white-1 {
  background: #fff;
  --bg2: #F8F8F8;
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #000;
  --border: rgba(0,0,0,.14);
}
.white-2 {
  background: #F8F8F8;
  --bg2: #ebebeb;
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #000;
  --border: rgba(0,0,0,.14);
}
.white-3 {
  background: rgb(212, 212, 212);
  --bg2: rgb(202, 202, 202);
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #000;
  --border: rgba(0,0,0,.14);
}
.white-4 {
  background: #1e1e1e;
  --bg2: #292929;
  --color-1: #fff;
  --color-2: #fff;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #fff;
  --border: rgba(255,255,255,0.1);
}
.white-5 {
  background: linear-gradient(#fff,#F8F8F8);
  --bg2: #ebebeb;
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #000;
  --border: rgba(0,0,0,.14);
}
.black-1 {
  background: #000;
  --bg2: #4D4D4D;
  --color-1: #fff;
  --color-2: #C5C5C5;
  --accent: #0073ff;
  --svg: #fff;
  --border: #404040;
}
.black-2 {
  background: #303030;
  --bg2: #4D4D4D;
  --color-1: #fff;
  --color-2: #C5C5C5;
  --accent: #0073ff;
  --svg: #fff;
  --border: #404040;
}
.black-3 {
  background: #4D4D4D;
  --bg2: #6A6A6A;
  --color-1: #fff;
  --color-2: #C5C5C5;
  --accent: #0073ff;
  --svg: #fff;
  --border: #676767;
}
.black-4 {
  background: #fff;
  --bg2: #f1f1f1;
  --color-1: #000;
  --color-2: #777;
  --accent: #0073ff;
  --svg: #fff;
  --border: #e7e7e7;
}
.black-5 {
  background: linear-gradient(#000,#4D4D4D);
  --bg2: #4D4D4D;
  --color-1: #fff;
  --color-2: #C5C5C5;
  --accent: #0073ff;
  --svg: #fff;
  --border: #404040;
}
.lightblue-1 {
  background: #D2DCE4;
  --bg2: #E7EEF3;
  --color-1: #1C252F;
  --color-2: #313F4E;
  --color-3: #fff;
  --accent: #56ACCC;
  --svg: #fff;
  --border: #C5CFD7;
}
.lightblue-2 {
  background: #E7EEF3;
  --bg2: #FCFDFD;
  --color-1: #1C252F;
  --color-2: #313F4E;
  --color-3: #fff;
  --accent: #56ACCC;
  --svg: #fff;
  --border: #C5CFD7;
}
.lightblue-3 {
  background: #B7C0C7;
  --bg2: #D5DDE3;
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #fff;
  --border: #A8B0B7;
}
.lightblue-4 {
  background: #fff;
  --bg2: #E7EEF3;
  --color-1: #1C252F;
  --color-2: #313F4E;
  --color-3: #fff;
  --accent: #56ACCC;
  --svg: #fff;
  --border: #C5CFD7;
}
.lightblue-5 {
  background: linear-gradient(#D2DCE4,#B7C0C7);
  --bg2: #ebebeb;
  --color-1: #000;
  --color-2: #000;
  --color-3: #fff;
  --accent: #0073FF;
  --svg: #fff;
  --border: rgba(0,0,0,.14);
}
.blue-1 {
  background: #1E85FF;
  --bg2: #013281;
  --color-1: #fff;
  --color-2: #D4E1F2;
  --accent: #53D1BD;
  --svg: #fff;
  --border: #1B77E6;
}
.blue-2 {
  background: #62AAFF;
  --bg2: #013281;
  --color-1: #fff;
  --color-2: #D4E1F2;
  --accent: #53D1BD;
  --svg: #fff;
  --border: #529FFA;
}
.blue-3 {
  background: #013281;
  --bg2: #001A43;
  --color-1: #fff;
  --color-2: #D4E1F2;
  --accent: #53D1BD;
  --svg: #fff;
  --border: #07439C;
}
.blue-4 {
  background: #000;
  --bg2: #001A43;
  --color-1: #fff;
  --color-2: #D4E1F2;
  --accent: #53D1BD;
  --svg: #fff;
  --border: rgba(255,255,255,.15);
}
.blue-5 {
  background: linear-gradient(#1E85FF,#62AAFF);
  --bg2: #013281;
  --color-1: #fff;
  --color-2: #D4E1F2;
  --accent: #53D1BD;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.pink-1 {
  background: #403530;
  --bg2: #53453F;
  --color-1: #FFCCC9;
  --color-2: #FFCCC9;
  --accent: #fff;
  --svg: #403530;
  --border: #5A514D;
}
.pink-2 {
  background: #6D5C54;
  --bg2: #53453F;
  --color-1: #FFCCC9;
  --color-2: #FFCCC9;
  --accent: #fff;
  --svg: #403530;
  --border: #5A514D;
}
.pink-3 {
  background: #2B2320;
  --bg2: #53453F;
  --color-1: #FFCCC9;
  --color-2: #FFCCC9;
  --accent: #fff;
  --svg: #403530;
  --border: #5A514D;
}
.pink-4 {
  background: #000;
  --bg2: #53453F;
  --color-1: #FFCCC9;
  --color-2: #FFCCC9;
  --accent: #fff;
  --svg: #403530;
  --border: #5A514D;
}
.pink-5 {
  background: linear-gradient(#403530,#6D5C54);
  --bg2: #53453F;
  --color-1: #FFCCC9;
  --color-2: #FFCCC9;
  --accent: #fff;
  --svg: #403530;
  --border: #5A514D;
}
.red-1 {
  background: #DF2E2C;
  --bg2: #E35350;
  --color-1: #fff;
  --color-2: rgba(255,255,255,.8);
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(255,255,255,.1);
}
.red-2 {
  background: #988989;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: rgba(255,255,255,.8);
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.red-3 {
  background: #A1B7E2;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: rgba(255,255,255,.8);
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(255,255,255,.1);
}
.red-4 {
  background: #000;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #FFCCC9;
  --color-3: #fff;
  --accent: #DF2E2C;
  --svg: #fff;
  --border: rgba(255,255,255,.2);
}
.red-5 {
  background: linear-gradient(#A97D82,#DF2E2C);
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #FFCCC9;
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.yellow-1 {
  background: #FBE7A5;
  --bg2: #DFD090;
  --color-1: #000000;
  --color-2: #5F573E;
  --color-3: #fff;
  --accent: #002DFF;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.yellow-2 {
  background: #FFFAE7;
  --bg2: #FEF3D2;
  --color-1: #000000;
  --color-2: #5F573E;
  --color-3: #fff;
  --accent: #002DFF;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.yellow-3 {
  background: #CBC7B8;
  --bg2: #DFD090;
  --color-1: #000000;
  --color-2: #5F573E;
  --color-3: #fff;
  --accent: #002DFF;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.yellow-4 {
  background: #FFFFFF;
  --bg2: #FFF2C6;
  --color-1: #000000;
  --color-2: #5F573E;
  --color-3: #fff;
  --accent: #002DFF;
  --svg: #fff;
  --border: rgba(0,0,0,0.11);
}
.yellow-5 {
  background: linear-gradient(#CBC7B8,#FFF2C6);
  --bg2: #DFD090;
  --color-1: #000000;
  --color-2: #5F573E;
  --color-3: #fff;
  --accent: #002DFF;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.orange-1 {
  background: #E76E35;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #fff;
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(255,255,255,.1);
}
.orange-2 {
  background: #FFE6DB;
  --bg2: #FAD0BC;
  --color-1: #E76E35;
  --color-2: #312C2A;
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.orange-3 {
  background: #3B74A9;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #FFE6;
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(255,255,255,.1);
}
.orange-4 {
  background: #000;
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #FFE6db;
  --color-3: #fff;
  --accent: #E76E35;
  --svg: #fff;
  --border: rgba(255,255,255,.2);
}
.orange-5 {
  background: linear-gradient(#E76E35,#3B74A9);
  --bg2: rgba(255,255,255,.2);
  --color-1: #fff;
  --color-2: #ffffffc7;
  --color-3: #fff;
  --accent: #000;
  --svg: #fff;
  --border: rgba(0,0,0,.1);
}
.green-1 {
  background: #2C5F5B;
  --bg2: #3D6C68;
  --color-1: #FFFFFF;
  --color-2: #C5D3D2;
  --color-3: #fff;
  --accent: #A3B745;
  --svg: #fff;
  --border: rgba(255,255,255,.2);
}
.green-2 {
  background: #809188;
  --bg2: #979797;
  --color-1: #FFFFFF;
  --color-2: #C5D3D2;
  --color-3: #000;
  --accent: #d9fa9e;
  --svg: #000;
  --border: rgba(255,255,255,.2);
}
.green-3 {
  background: #87766A;
  --bg2: #979797;
  --color-1: #FFFFFF;
  --color-2: #F4EEEA;
  --color-3: #000;
  --accent: #d9fa9e;
  --svg: #000;
  --border: rgba(255,255,255,.2);
}
.green-4 {
  background: #FFFFFF;
  --bg2: #979797;
  --color-1: #2C5F5B;
  --color-2: #373737;
  --color-3: #fff;
  --accent: #829F50;
  --svg: #fff;
  --border: rgba(0,0,0,0.1);
}
.green-5 {
  background: linear-gradient(#2C5F5B,#87766A);
  --bg2: #979797;
  --color-1: #FFFFFF;
  --color-2: #C5D3D2;
  --color-3: #fff;
  --accent: #829F50;
  --svg: #fff;
  --border: rgba(123,138,131,1);
}

</style>
    <title>Gohashindi Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Give Me Your Support For My Project">
    <meta property="og:image" content="">
    <link rel="shortcut icon" href="https://cdn.visual.com/images/ee116861-9714-4738-a703-f0807db46eb7.jpg">
    
    
    
    
    
    
    
    <style>
      
    </style>
    <style>
      
    </style>
    
    
  </head><body>
  
  
  
  <div><div class="template-3 startup font-1" activepageblocks="b_225a7e11-a720-4b3f-955a-fb533a11e263" logos="[object Object]" sortedkeys="0408b623-66fb-4205-91c9-4b6a4be39ea5"><div class="white-2" id="hero-0" blockid="b_225a7e11-a720-4b3f-955a-fb533a11e263" blockindex="0" blogs="[object Object]"><header class="header-flex"><div><div class="header-flex-logo"><div class="choose-logo-container-item logo-large"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 543 90" class="iconLeft"><!----><!----><!----><g id="364afa57-4b82-4560-b1a1-c5b420ebe9bd" fill="#0F104F" transform="matrix(4.889975367689235,0,0,4.889975367689235,107.26160548493462,20.96576937322845)"><path d="M5.08 5.14L5.08 6.93L6.55 6.97L6.55 8.54C6.55 8.54 6.08 8.89 5.35 8.89C4.34 8.89 3.78 7.88 3.78 5.89C3.78 3.78 4.51 3.12 5.45 3.12C6.65 3.12 7.99 3.85 7.99 3.85L8.74 1.47C8.74 1.47 7.28 0.80 5.50 0.80C2.67 0.80 0.56 2.55 0.56 5.92C0.56 9.37 2.44 11.05 5.31 11.05C7.46 11.05 8.68 9.35 9.34 9.35L9.34 5.14ZM15.12 2.95C12.28 2.95 10.53 4.48 10.53 6.97C10.53 9.42 12.28 11.05 15.12 11.05C17.99 11.05 19.71 9.42 19.71 6.97C19.71 4.48 17.99 2.95 15.12 2.95ZM13.80 6.92C13.80 5.71 14.11 5.00 15.05 5.00C16.10 5.00 16.42 6.02 16.42 7.04C16.42 8.05 16.18 8.95 15.13 8.95C14.20 8.95 13.80 8.11 13.80 6.92ZM24.43 7.01C24.43 6.01 24.60 5.04 25.49 5.04C26.40 5.04 26.64 5.82 26.64 7.01C26.64 8.22 26.59 9.91 26.59 10.79L29.97 10.79C29.93 9.90 29.88 7.90 29.88 5.74C29.88 3.95 28.91 2.95 27.20 2.95C25.69 2.95 24.82 3.61 24.46 4.51C24.51 2.39 24.67-0.97 24.67-0.97C24.67-0.97 22.69-0.88 21.11-0.97C21.11-0.97 21.27 2.79 21.27 4.94C21.27 7.73 21.15 10.79 21.15 10.79L24.53 10.79C24.49 9.87 24.43 8.18 24.43 7.01ZM36.34 10.79L39.35 10.79C39.35 10.79 39.09 7.31 39.09 5.78C39.09 4.02 37.98 2.95 35.43 2.95C32.89 2.95 31.32 4.20 31.32 4.20L31.92 6.17C31.92 6.17 33.68 4.98 35.03 4.98C35.67 4.98 36.08 5.26 36.08 5.75C36.08 6.79 31.16 6.10 31.16 9.06C31.16 10.37 32.19 11.05 33.49 11.05C35.43 11.05 35.97 10.07 36.32 9.28ZM35.03 9.24C34.72 9.24 34.51 9.07 34.51 8.62C34.51 7.69 35.64 7.41 36.23 6.97C36.12 8.13 35.73 9.24 35.03 9.24ZM47.07 3.79C47.07 3.79 45.91 2.95 44.03 2.95C42.25 2.95 40.67 3.79 40.67 5.46C40.67 7.98 44.11 7.77 44.11 8.74C44.11 8.96 43.96 9.14 43.65 9.14C42.53 9.14 40.91 8.04 40.91 8.04L40.15 9.73C40.15 9.73 41.45 11.05 44.09 11.05C46.05 11.05 47.35 9.98 47.35 8.47C47.35 5.91 43.67 6.15 43.67 5.28C43.67 5.04 43.82 4.82 44.16 4.82C45.04 4.82 46.48 5.54 46.48 5.54ZM51.86 7.01C51.86 6.01 52.02 5.04 52.92 5.04C53.83 5.04 54.07 5.82 54.07 7.01C54.07 8.22 54.01 9.91 54.01 10.79L57.40 10.79C57.36 9.90 57.30 7.90 57.30 5.74C57.30 3.95 56.34 2.95 54.63 2.95C53.12 2.95 52.25 3.61 51.88 4.51C51.94 2.39 52.09-0.97 52.09-0.97C52.09-0.97 50.12-0.88 48.54-0.97C48.54-0.97 48.69 2.79 48.69 4.94C48.69 7.73 48.58 10.79 48.58 10.79L51.95 10.79C51.91 9.87 51.86 8.18 51.86 7.01ZM62.64 0.56C62.64-0.42 61.84-1.22 60.86-1.22C59.88-1.22 59.08-0.42 59.08 0.56C59.08 1.55 59.88 2.34 60.86 2.34C61.84 2.34 62.64 1.55 62.64 0.56ZM62.50 10.79C62.43 7.87 62.44 8.48 62.44 7C62.44 5.40 62.61 3.21 62.61 3.21C62.61 3.21 60.68 3.29 59.11 3.21C59.11 3.21 59.25 5.38 59.25 6.93C59.25 8.61 59.15 10.79 59.15 10.79ZM69.72 10.79L73.12 10.79C73.09 9.90 73.02 8.09 73.02 5.74C73.02 4.07 72.17 2.95 70.32 2.95C68.85 2.95 67.97 3.61 67.61 4.51L67.65 3.21L64.39 3.21C64.40 3.82 64.40 4.42 64.40 4.94C64.40 7.73 64.30 10.79 64.30 10.79L67.66 10.79C67.62 9.87 67.56 8.18 67.56 7.01C67.56 6.01 67.73 5.04 68.64 5.04C69.54 5.04 69.79 5.82 69.79 7.01C69.79 8.22 69.73 9.91 69.72 10.79ZM74.52 7.01C74.52 9.51 75.94 11.05 78.01 11.05C79.44 11.05 80.21 10.09 80.46 9.32C80.46 9.55 80.50 10.54 80.50 10.79L83.85 10.79C83.85 10.79 83.62 9.52 83.59 7.88C83.58 6.97 83.58 5.94 83.58 4.94C83.58 2.79 83.72-0.97 83.72-0.97C82.15-0.88 80.16-0.97 80.16-0.97C80.16-0.97 80.33 2.39 80.37 4.51C80.01 3.61 79.14 2.95 77.97 2.95C75.89 2.95 74.52 4.54 74.52 7.01ZM77.90 7.01C77.90 5.82 78.25 5.04 79.18 5.04C80.23 5.04 80.58 6.01 80.58 7.01C80.58 8.04 80.23 8.99 79.18 8.99C78.25 8.99 77.90 8.22 77.90 7.01ZM88.94 0.56C88.94-0.42 88.14-1.22 87.16-1.22C86.18-1.22 85.39-0.42 85.39 0.56C85.39 1.55 86.18 2.34 87.16 2.34C88.14 2.34 88.94 1.55 88.94 0.56ZM88.80 10.79C88.73 7.87 88.75 8.48 88.75 7C88.75 5.40 88.91 3.21 88.91 3.21C88.91 3.21 86.98 3.29 85.41 3.21C85.41 3.21 85.55 5.38 85.55 6.93C85.55 8.61 85.46 10.79 85.46 10.79Z"></path></g><defs><linearGradient gradientTransform="rotate(25)" id="4fb9b9ba-d02d-4177-815e-32311795a640" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color: rgb(13, 33, 117); stop-opacity: 1;"></stop><stop offset="100%" style="stop-color: rgb(31, 30, 251); stop-opacity: 1;"></stop></linearGradient></defs><g id="5cee9ee5-a5cb-4f9b-b633-12cdbe3f5aa6" transform="matrix(2.8125,0,0,2.8125,0,0)" stroke="none" fill="url(#4fb9b9ba-d02d-4177-815e-32311795a640)"><path d="M9.382 8.675h13.943v13.943L32 31.293V0H.707zM22.618 23.325H8.675V9.382L0 .707V32h31.293z"></path></g><!----></svg></div><!----></div></div><nav class="header-menu"><!----><div class="menu"><a href="#mobile-menu">Menu</a></div></nav><div id="mobile-menu" class="overlay"><a href="#" class="close">Close</a><!----></div></header><section class="large"><div class="hero"><div class="container"><div class="container-small"><div class="hero-image"><h1 class="primary-color">Gohashindi Project</h1><div class="subheading freeform mt20 secondary-color"><div><div class="ProseMirror" contenteditable="false" tabindex="0"><p>Give Your Support For me on cloud and project Gaming</p></div></div></div><div class="cta mt40"><a target="_blank" href="https://square.link/u/pDidCWQY" class="accent">Donate</a></div></div></div><!----><!----><!----><!----><!----><!----></div></div></section></div><footer class="freeform white-2" id="footer" blogs="[object Object]" meta="[object Object]"><div class="footer-flex"><div><b class="title">GOHASHINDI</b><span class="span">Gohashindi Co LTD</span></div><nav><ul><li><a href="https://t.me/bernandfixer" class="navlink">@bernandfixer</a></li></ul><ul></ul></nav></div></footer></div></div>
    
    
  </body>
  </html>
  