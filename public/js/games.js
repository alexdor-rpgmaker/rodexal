!function(t){var e={};function r(n){if(e[n])return e[n].exports;var o=e[n]={i:n,l:!1,exports:{}};return t[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=t,r.c=e,r.d=function(t,e,n){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},r.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)r.d(n,o,function(e){return t[e]}.bind(null,o));return n},r.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="/",r(r.s=51)}({0:function(t,e,r){t.exports=r(12)},12:function(t,e,r){var n=function(t){"use strict";var e=Object.prototype,r=e.hasOwnProperty,n="function"==typeof Symbol?Symbol:{},o=n.iterator||"@@iterator",a=n.asyncIterator||"@@asyncIterator",s=n.toStringTag||"@@toStringTag";function i(t,e,r,n){var o=e&&e.prototype instanceof l?e:l,a=Object.create(o.prototype),s=new C(n||[]);return a._invoke=function(t,e,r){var n="suspendedStart";return function(o,a){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw a;return x()}for(r.method=o,r.arg=a;;){var s=r.delegate;if(s){var i=w(s,r);if(i){if(i===u)continue;return i}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var l=c(t,e,r);if("normal"===l.type){if(n=r.done?"completed":"suspendedYield",l.arg===u)continue;return{value:l.arg,done:r.done}}"throw"===l.type&&(n="completed",r.method="throw",r.arg=l.arg)}}}(t,r,s),a}function c(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}t.wrap=i;var u={};function l(){}function f(){}function d(){}var p={};p[o]=function(){return this};var v=Object.getPrototypeOf,h=v&&v(v(k([])));h&&h!==e&&r.call(h,o)&&(p=h);var m=d.prototype=l.prototype=Object.create(p);function g(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function y(t,e){var n;this._invoke=function(o,a){function s(){return new e((function(n,s){!function n(o,a,s,i){var u=c(t[o],t,a);if("throw"!==u.type){var l=u.arg,f=l.value;return f&&"object"==typeof f&&r.call(f,"__await")?e.resolve(f.__await).then((function(t){n("next",t,s,i)}),(function(t){n("throw",t,s,i)})):e.resolve(f).then((function(t){l.value=t,s(l)}),(function(t){return n("throw",t,s,i)}))}i(u.arg)}(o,a,n,s)}))}return n=n?n.then(s,s):s()}}function w(t,e){var r=t.iterator[e.method];if(void 0===r){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=void 0,w(t,e),"throw"===e.method))return u;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return u}var n=c(r,t.iterator,e.arg);if("throw"===n.type)return e.method="throw",e.arg=n.arg,e.delegate=null,u;var o=n.arg;return o?o.done?(e[t.resultName]=o.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=void 0),e.delegate=null,u):o:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,u)}function _(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function b(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function C(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(_,this),this.reset(!0)}function k(t){if(t){var e=t[o];if(e)return e.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var n=-1,a=function e(){for(;++n<t.length;)if(r.call(t,n))return e.value=t[n],e.done=!1,e;return e.value=void 0,e.done=!0,e};return a.next=a}}return{next:x}}function x(){return{value:void 0,done:!0}}return f.prototype=m.constructor=d,d.constructor=f,d[s]=f.displayName="GeneratorFunction",t.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===f||"GeneratorFunction"===(e.displayName||e.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,d):(t.__proto__=d,s in t||(t[s]="GeneratorFunction")),t.prototype=Object.create(m),t},t.awrap=function(t){return{__await:t}},g(y.prototype),y.prototype[a]=function(){return this},t.AsyncIterator=y,t.async=function(e,r,n,o,a){void 0===a&&(a=Promise);var s=new y(i(e,r,n,o),a);return t.isGeneratorFunction(r)?s:s.next().then((function(t){return t.done?t.value:s.next()}))},g(m),m[s]="Generator",m[o]=function(){return this},m.toString=function(){return"[object Generator]"},t.keys=function(t){var e=[];for(var r in t)e.push(r);return e.reverse(),function r(){for(;e.length;){var n=e.pop();if(n in t)return r.value=n,r.done=!1,r}return r.done=!0,r}},t.values=k,C.prototype={constructor:C,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(b),!t)for(var e in this)"t"===e.charAt(0)&&r.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function n(r,n){return s.type="throw",s.arg=t,e.next=r,n&&(e.method="next",e.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var a=this.tryEntries[o],s=a.completion;if("root"===a.tryLoc)return n("end");if(a.tryLoc<=this.prev){var i=r.call(a,"catchLoc"),c=r.call(a,"finallyLoc");if(i&&c){if(this.prev<a.catchLoc)return n(a.catchLoc,!0);if(this.prev<a.finallyLoc)return n(a.finallyLoc)}else if(i){if(this.prev<a.catchLoc)return n(a.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return n(a.finallyLoc)}}}},abrupt:function(t,e){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===t||"continue"===t)&&a.tryLoc<=e&&e<=a.finallyLoc&&(a=null);var s=a?a.completion:{};return s.type=t,s.arg=e,a?(this.method="next",this.next=a.finallyLoc,u):this.complete(s)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),u},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),b(r),u}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;b(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,r){return this.delegate={iterator:k(t),resultName:e,nextLoc:r},"next"===this.method&&(this.arg=void 0),u}},t}(t.exports);try{regeneratorRuntime=n}catch(t){Function("r","regeneratorRuntime = r")(n)}},3:function(t,e,r){"use strict";function n(t,e,r,n,o,a,s,i){var c,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=r,u._compiled=!0),n&&(u.functional=!0),a&&(u._scopeId="data-v-"+a),s?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},u._ssrRegister=c):o&&(c=i?function(){o.call(this,this.$root.$options.shadowRoot)}:o),c)if(u.functional){u._injectStyles=c;var l=u.render;u.render=function(t,e){return c.call(e),l(t,e)}}else{var f=u.beforeCreate;u.beforeCreate=f?[].concat(f,c):[c]}return{exports:t,options:u}}r.d(e,"a",(function(){return n}))},4:function(t,e){t.exports=function(t){var e=[];return e.toString=function(){return this.map((function(e){var r=function(t,e){var r=t[1]||"",n=t[3];if(!n)return r;if(e&&"function"==typeof btoa){var o=(s=n,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),a=n.sources.map((function(t){return"/*# sourceURL="+n.sourceRoot+t+" */"}));return[r].concat(a).concat([o]).join("\n")}var s;return[r].join("\n")}(e,t);return e[2]?"@media "+e[2]+"{"+r+"}":r})).join("")},e.i=function(t,r){"string"==typeof t&&(t=[[null,t,""]]);for(var n={},o=0;o<this.length;o++){var a=this[o][0];"number"==typeof a&&(n[a]=!0)}for(o=0;o<t.length;o++){var s=t[o];"number"==typeof s[0]&&n[s[0]]||(r&&!s[2]?s[2]=r:r&&(s[2]="("+s[2]+") and ("+r+")"),e.push(s))}},e}},5:function(t,e,r){var n,o,a={},s=(n=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===o&&(o=n.apply(this,arguments)),o}),i=function(t,e){return e?e.querySelector(t):document.querySelector(t)},c=function(t){var e={};return function(t,r){if("function"==typeof t)return t();if(void 0===e[t]){var n=i.call(this,t,r);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(t){n=null}e[t]=n}return e[t]}}(),u=null,l=0,f=[],d=r(7);function p(t,e){for(var r=0;r<t.length;r++){var n=t[r],o=a[n.id];if(o){o.refs++;for(var s=0;s<o.parts.length;s++)o.parts[s](n.parts[s]);for(;s<n.parts.length;s++)o.parts.push(w(n.parts[s],e))}else{var i=[];for(s=0;s<n.parts.length;s++)i.push(w(n.parts[s],e));a[n.id]={id:n.id,refs:1,parts:i}}}}function v(t,e){for(var r=[],n={},o=0;o<t.length;o++){var a=t[o],s=e.base?a[0]+e.base:a[0],i={css:a[1],media:a[2],sourceMap:a[3]};n[s]?n[s].parts.push(i):r.push(n[s]={id:s,parts:[i]})}return r}function h(t,e){var r=c(t.insertInto);if(!r)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var n=f[f.length-1];if("top"===t.insertAt)n?n.nextSibling?r.insertBefore(e,n.nextSibling):r.appendChild(e):r.insertBefore(e,r.firstChild),f.push(e);else if("bottom"===t.insertAt)r.appendChild(e);else{if("object"!=typeof t.insertAt||!t.insertAt.before)throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");var o=c(t.insertAt.before,r);r.insertBefore(e,o)}}function m(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t);var e=f.indexOf(t);e>=0&&f.splice(e,1)}function g(t){var e=document.createElement("style");if(void 0===t.attrs.type&&(t.attrs.type="text/css"),void 0===t.attrs.nonce){var n=function(){0;return r.nc}();n&&(t.attrs.nonce=n)}return y(e,t.attrs),h(t,e),e}function y(t,e){Object.keys(e).forEach((function(r){t.setAttribute(r,e[r])}))}function w(t,e){var r,n,o,a;if(e.transform&&t.css){if(!(a="function"==typeof e.transform?e.transform(t.css):e.transform.default(t.css)))return function(){};t.css=a}if(e.singleton){var s=l++;r=u||(u=g(e)),n=C.bind(null,r,s,!1),o=C.bind(null,r,s,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(r=function(t){var e=document.createElement("link");return void 0===t.attrs.type&&(t.attrs.type="text/css"),t.attrs.rel="stylesheet",y(e,t.attrs),h(t,e),e}(e),n=x.bind(null,r,e),o=function(){m(r),r.href&&URL.revokeObjectURL(r.href)}):(r=g(e),n=k.bind(null,r),o=function(){m(r)});return n(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;n(t=e)}else o()}}t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(e=e||{}).attrs="object"==typeof e.attrs?e.attrs:{},e.singleton||"boolean"==typeof e.singleton||(e.singleton=s()),e.insertInto||(e.insertInto="head"),e.insertAt||(e.insertAt="bottom");var r=v(t,e);return p(r,e),function(t){for(var n=[],o=0;o<r.length;o++){var s=r[o];(i=a[s.id]).refs--,n.push(i)}t&&p(v(t,e),e);for(o=0;o<n.length;o++){var i;if(0===(i=n[o]).refs){for(var c=0;c<i.parts.length;c++)i.parts[c]();delete a[i.id]}}}};var _,b=(_=[],function(t,e){return _[t]=e,_.filter(Boolean).join("\n")});function C(t,e,r,n){var o=r?"":n.css;if(t.styleSheet)t.styleSheet.cssText=b(e,o);else{var a=document.createTextNode(o),s=t.childNodes;s[e]&&t.removeChild(s[e]),s.length?t.insertBefore(a,s[e]):t.appendChild(a)}}function k(t,e){var r=e.css,n=e.media;if(n&&t.setAttribute("media",n),t.styleSheet)t.styleSheet.cssText=r;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(r))}}function x(t,e,r){var n=r.css,o=r.sourceMap,a=void 0===e.convertToAbsoluteUrls&&o;(e.convertToAbsoluteUrls||a)&&(n=d(n)),o&&(n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */");var s=new Blob([n],{type:"text/css"}),i=t.href;t.href=URL.createObjectURL(s),i&&URL.revokeObjectURL(i)}},51:function(t,e,r){t.exports=r(62)},52:function(t,e,r){"use strict";var n=r(8);r.n(n).a},53:function(t,e,r){(t.exports=r(4)(!1)).push([t.i,".tr td[data-v-2220c39e] {\n  text-align: left;\n}\n.makers a.color-member[data-v-2220c39e],\n.makers a.color-challenger[data-v-2220c39e],\n.makers a.color-ambassador[data-v-2220c39e],\n.makers a.color-juror[data-v-2220c39e],\n.makers a.color-moderator[data-v-2220c39e],\n.makers a.color-administrator[data-v-2220c39e],\n.makers a.color-webmaster[data-v-2220c39e] {\n  font-weight: bold;\n}\n.makers a.color-member[data-v-2220c39e] {\n  color: #2954ff;\n}\n.makers a.color-challenger[data-v-2220c39e] {\n  color: #0b00a1;\n}\n.makers a.color-ambassador[data-v-2220c39e] {\n  color: #00acb8;\n}\n.makers a.color-juror[data-v-2220c39e] {\n  color: #9f3ad1;\n}\n.makers a.color-moderator[data-v-2220c39e] {\n  color: #269600;\n}\n.makers a.color-administrator[data-v-2220c39e],\n.makers a.color-webmaster[data-v-2220c39e] {\n  color: #cf0000;\n}\n.awarded-categories[data-v-2220c39e], .nominated-categories[data-v-2220c39e] {\n  font-style: italic;\n}",""])},54:function(t,e,r){"use strict";var n=r(9);r.n(n).a},55:function(t,e,r){(t.exports=r(4)(!1)).push([t.i,"#games-list .title[data-v-25cf397e], #games-list .session[data-v-25cf397e], #games-list .software[data-v-25cf397e], #games-list .genre[data-v-25cf397e] {\n  cursor: pointer;\n}\n#games-list .author[data-v-25cf397e] {\n  width: 160px;\n}\n#games-list .software[data-v-25cf397e], #games-list .genre[data-v-25cf397e] {\n  width: 130px;\n}\n#games-list .download[data-v-25cf397e] {\n  width: 50px;\n}",""])},62:function(t,e,r){"use strict";r.r(e);var n=r(0),o=r.n(n),a={props:{game:{type:Object,required:!0}},computed:{awardedCategories:function(){return this.game.awards||console.log(this.game),this.game.awards.filter((function(t){return"awarded"===t.status}))},awardedCategoriesList:function(){return this.awardedCategories.map(this.awardName).join(", ")},wasAwarded:function(){return this.awardedCategoriesList.length>0},nominatedCategories:function(){return this.game.awards.filter((function(t){return"nominated"===t.status}))},nominatedCategoriesList:function(){return this.nominatedCategories.map((function(t){return t.category_name})).join(", ")},wasNominated:function(){return this.nominatedCategories.length>0}},methods:{awardName:function(t){var e=t.category_name;switch(t.award_level){case"gold":e+=" (or)";break;case"silver":e+=" (argent)";break;case"bronze":e+=" (bronze)"}return e}}},s=(r(52),r(3));function i(t,e,r,n,o,a,s){try{var i=t[a](s),c=i.value}catch(t){return void r(t)}i.done?e(c):Promise.resolve(c).then(n,o)}function c(t){return function(){var e=this,r=arguments;return new Promise((function(n,o){var a=t.apply(e,r);function s(t){i(a,n,o,s,c,"next",t)}function c(t){i(a,n,o,s,c,"throw",t)}s(void 0)}))}}var u={components:{GameRow:Object(s.a)(a,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("tr",{staticClass:"tr"},[r("td",[t.game.screenshots[0]?[r("a",{staticClass:"screenshot-link",attrs:{href:t.formerAppUrl+"/?p=jeu&id="+t.game.id}},[r("img",{staticClass:"screenshot",staticStyle:{border:"none"},attrs:{src:t.game.screenshots[0].url,width:"100",alt:""}})])]:t._e()],2),t._v(" "),r("td",[r("p",{staticClass:"title mb-1"},[r("a",{staticClass:"title-link",attrs:{href:t.formerAppUrl+"/?p=jeu&id="+t.game.id}},[t._v("\n        "+t._s(t.game.title)+"\n      ")])]),t._v(" "),t.wasAwarded?r("p",{staticClass:"awarded-categories mb-1"},[t._v("\n      Victoire : "+t._s(t.awardedCategoriesList)+"\n    ")]):t._e(),t._v(" "),t.wasNominated?r("p",{staticClass:"nominated-categories mb-1"},[t._v("\n      Nominations : "+t._s(t.nominatedCategoriesList)+"\n    ")]):t._e()]),t._v(" "),r("td",{staticClass:"session"},[t._v(t._s(t.game.session.name))]),t._v(" "),r("td",{staticClass:"makers"},[t.game.creationGroup?r("span",[t._v(t._s(t.game.creationGroup)+" :")]):t._e(),t._v(" "),t._l(t.game.authors,(function(e,n){return r("span",{key:e.id+e.username},[e.id?r("a",{class:"color-"+e.rank,attrs:{href:t.formerAppUrl+"/?p=profil&membre="+e.id}},[t._v(t._s(e.username))]):[t._v(t._s(e.username))],n<t.game.authors.length-1?[t._v(", ")]:t._e()],2)}))],2),t._v(" "),r("td",{staticClass:"software"},[t._v(t._s(t.game.software))]),t._v(" "),r("td",{staticClass:"genre"},[t._v(t._s(t.game.genre))]),t._v(" "),r("td",{staticClass:"download-links"},t._l(t.game.downloadLinks,(function(e){return r("a",{key:e.platform,attrs:{href:e.url}},[r("img",{staticStyle:{border:"none"},attrs:{src:t.formerAppUrl+"/design/divers/disquette-verte.gif",alt:"Disquette"}}),t._v(" "),"windows"===e.platform?r("span",[t._v("(Win)")]):r("span",[t._v("(Mac)")])])})),0)])}),[],!1,null,"2220c39e",null).exports},props:{session:{type:Number,required:!1}},data:function(){return{games:[],query:null,page:1,totalPagesCount:1,resultsCountOnThisPage:null,totalResultsCount:null,selectedSoftware:null,selectedSession:null,selectedSort:"awards_count",withDownloadLinks:!1,sortDirection:"desc",sessions:[1,2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,19,20],softwares:["adventure game studio","AINSI","Autre","Clickteam Fusion 2.5","Game Maker Studio 2","Geex / Rpg Maker Xp","Klik & Game","MMF2","MPEG-1/2 Audio Layer","Mugen","Multimedia Fusion 2","RMVX","RPG m'écoeure 2003","RPG maker","RPG Maker 2000","RPG Maker 2003","Rpg maker 95","RPG Maker MV","RPG Maker VX","RPG Maker VX Ace","RPG Maker XP","The Games Factory 1.06","Unity"]}},mounted:function(){var t=this;return c(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.session&&(t.selectedSession=t.session),e.next=3,t.fetchGames();case 3:case"end":return e.stop()}}),e)})))()},computed:{notLastPage:function(){return this.page<this.totalPagesCount},gamesCount:function(){return this.resultsCountOnThisPage===this.totalResultsCount?this.resultsCountOnThisPage:"".concat(this.resultsCountOnThisPage," sur ").concat(this.totalResultsCount)}},methods:{fetchGames:function(){var t=this;return c(o.a.mark((function e(){var r,n;return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r={page:t.page},t.query&&(r.q=t.query),t.selectedSoftware&&(r.software=t.selectedSoftware),t.selectedSession&&(r.session_id=t.selectedSession),t.withDownloadLinks&&(r.download_links="any"),t.selectedSort&&(r.sort="".concat(t.selectedSort,":").concat(t.sortDirection),"title"!==t.selectedSort&&(r.sort+=",title:asc")),e.next=8,axios({url:"/api/v0/games",params:r});case 8:n=e.sent,t.page=n.data.current_page,t.totalPagesCount=n.data.last_page,t.totalResultsCount=n.data.total,t.resultsCountOnThisPage=n.data.data.length,t.games=n.data.data.map(t.formatGameForList);case 14:case"end":return e.stop()}}),e)})))()},search:function(){var t=this;return c(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.page=1,e.next=3,t.fetchGames();case 3:case"end":return e.stop()}}),e)})))()},previousPage:function(){var t=this;return c(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return window.scrollTo(0,0),t.page-=1,e.next=4,t.fetchGames();case 4:case"end":return e.stop()}}),e)})))()},nextPage:function(){var t=this;return c(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return window.scrollTo(0,0),t.page+=1,e.next=4,t.fetchGames();case 4:case"end":return e.stop()}}),e)})))()},sortBy:function(t){var e=this;return c(o.a.mark((function r(){return o.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return e.selectedSort===t&&(e.sortDirection="asc"!==e.sortDirection?"asc":"desc"),e.selectedSort=t,r.next=4,e.search();case 4:case"end":return r.stop()}}),r)})))()},sessionName:function(t){return 3===t?"Session 2003-2004":17===t?"Session 2017-2018":"Session ".concat(t+2e3)},formatGameForList:function(t){return{id:t.id,title:t.title,genre:t.genre,authors:t.authors,session:t.session,software:t.software,screenshots:t.screenshots,description:t.description,creationGroup:t.creation_group,downloadLinks:t.download_links,awards:t.awards}}}},l=(r(54),Object(s.a)(u,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{attrs:{id:"games-list"}},[r("div",{staticClass:"container"},[r("div",{staticClass:"justify-content-center"},[r("div",{attrs:{id:"games-wrapper"}},[r("form",{staticClass:"games-form",on:{submit:function(e){return e.preventDefault(),t.search(e)}}},[r("div",{staticClass:"row"},[r("div",{staticClass:"col"},[r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-6"},[r("div",{staticClass:"form-group"},[r("label",{attrs:{for:"session"}},[t._v("Session")]),t._v(" "),r("select",{directives:[{name:"model",rawName:"v-model",value:t.selectedSession,expression:"selectedSession"}],staticClass:"custom-select",attrs:{id:"session",name:"session"},on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selectedSession=e.target.multiple?r:r[0]}}},[r("option",{domProps:{value:null}},[t._v("(Toutes les sessions)")]),t._v(" "),t._l(t.sessions,(function(e){return r("option",{key:e,domProps:{value:e}},[t._v(t._s(t.sessionName(e))+"\n                      ")])}))],2)])]),t._v(" "),r("div",{staticClass:"col-md-6"},[r("div",{staticClass:"form-group"},[r("label",{attrs:{for:"software"}},[t._v("Logiciels")]),t._v(" "),r("select",{directives:[{name:"model",rawName:"v-model",value:t.selectedSoftware,expression:"selectedSoftware"}],staticClass:"custom-select",attrs:{id:"software",name:"software"},on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.selectedSoftware=e.target.multiple?r:r[0]}}},[r("option",{domProps:{value:null}},[t._v("(Tous les logiciels)")]),t._v(" "),t._l(t.softwares,(function(e){return r("option",{key:e,domProps:{value:e}},[t._v(t._s(e))])}))],2)])])]),t._v(" "),r("div",{staticClass:"row"},[r("div",{staticClass:"col-md-12"},[r("div",{staticClass:"form-group"},[r("label",{attrs:{for:"query"}},[t._v("Recherche")]),t._v(" "),r("input",{directives:[{name:"model",rawName:"v-model",value:t.query,expression:"query"}],staticClass:"form-control",attrs:{id:"query",name:"query",type:"text",placeholder:"Aventure, Humour, RuTiPa's Quest, ..."},domProps:{value:t.query},on:{input:function(e){e.target.composing||(t.query=e.target.value)}}})])])])]),t._v(" "),r("div",{staticClass:"col"},[r("div",{staticClass:"form-check form-group pt-4 mb-5"},[r("input",{directives:[{name:"model",rawName:"v-model",value:t.withDownloadLinks,expression:"withDownloadLinks"}],staticClass:"form-check-input",attrs:{id:"download-links",name:"download-links",type:"checkbox"},domProps:{checked:Array.isArray(t.withDownloadLinks)?t._i(t.withDownloadLinks,null)>-1:t.withDownloadLinks},on:{change:function(e){var r=t.withDownloadLinks,n=e.target,o=!!n.checked;if(Array.isArray(r)){var a=t._i(r,null);n.checked?a<0&&(t.withDownloadLinks=r.concat([null])):a>-1&&(t.withDownloadLinks=r.slice(0,a).concat(r.slice(a+1)))}else t.withDownloadLinks=o}}}),t._v(" "),r("label",{staticClass:"form-check-label",attrs:{for:"download-links"}},[t._v("Avec lien de téléchargement")])]),t._v(" "),r("button",{staticClass:"bouton",attrs:{type:"submit"}},[t._v("Rechercher")])])])])])]),t._v(" "),r("p",{staticClass:"mb-4"},[t._v("Nombre de jeux : "),r("strong",[t._v(t._s(t.gamesCount))]),t._v(".")])]),t._v(" "),r("table",{staticClass:"table"},[r("tr",{staticClass:"tableau_legend"},[r("th"),t._v(" "),r("th",{staticClass:"title",on:{click:function(e){return t.sortBy("title")}}},[t._v("Titre du Jeu")]),t._v(" "),r("th",{staticClass:"session",on:{click:function(e){return t.sortBy("session")}}},[t._v("Session")]),t._v(" "),r("th",{staticClass:"author"},[t._v("Auteur(s)")]),t._v(" "),r("th",{staticClass:"software",on:{click:function(e){return t.sortBy("software")}}},[t._v("Support")]),t._v(" "),r("th",{staticClass:"genre",on:{click:function(e){return t.sortBy("genre")}}},[t._v("Genre")]),t._v(" "),r("th",{staticClass:"download"},[t._v("Téléch.")])]),t._v(" "),t._l(t.games,(function(t){return r("game-row",{key:t.id,staticClass:"tr",attrs:{game:t}})}))],2),t._v(" "),t.page>1?r("button",{staticClass:"previous btn btn-primary mt-4",attrs:{type:"button"},on:{click:t.previousPage}},[t._v("Résultats précédents\n  ")]):t._e(),t._v(" "),t.notLastPage?r("button",{staticClass:"next btn btn-primary mt-4",attrs:{type:"button"},on:{click:t.nextPage}},[t._v("Résultats suivants\n  ")]):t._e()])}),[],!1,null,"25cf397e",null).exports);new Vue({el:"#games-wrapper",components:{Games:l}})},7:function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var r=e.protocol+"//"+e.host,n=r+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,(function(t,e){var o,a=e.trim().replace(/^"(.*)"$/,(function(t,e){return e})).replace(/^'(.*)'$/,(function(t,e){return e}));return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(a)?t:(o=0===a.indexOf("//")?a:0===a.indexOf("/")?r+a:n+a.replace(/^\.\//,""),"url("+JSON.stringify(o)+")")}))}},8:function(t,e,r){var n=r(53);"string"==typeof n&&(n=[[t.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};r(5)(n,o);n.locals&&(t.exports=n.locals)},9:function(t,e,r){var n=r(55);"string"==typeof n&&(n=[[t.i,n,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};r(5)(n,o);n.locals&&(t.exports=n.locals)}});