!function(e){var t={};function n(i){if(t[i])return t[i].exports;var r=t[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(i,r,function(t){return e[t]}.bind(null,r));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=43)}({2:function(e,t,n){var i=n(45);"string"==typeof i&&(i=[[e.i,i,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};n(41)(i,r);i.locals&&(e.exports=i.locals)},40:function(e,t){e.exports=function(e){var t=[];return t.toString=function(){return this.map(function(t){var n=function(e,t){var n=e[1]||"",i=e[3];if(!i)return n;if(t&&"function"==typeof btoa){var r=(s=i,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),o=i.sources.map(function(e){return"/*# sourceURL="+i.sourceRoot+e+" */"});return[n].concat(o).concat([r]).join("\n")}var s;return[n].join("\n")}(t,e);return t[2]?"@media "+t[2]+"{"+n+"}":n}).join("")},t.i=function(e,n){"string"==typeof e&&(e=[[null,e,""]]);for(var i={},r=0;r<this.length;r++){var o=this[r][0];"number"==typeof o&&(i[o]=!0)}for(r=0;r<e.length;r++){var s=e[r];"number"==typeof s[0]&&i[s[0]]||(n&&!s[2]?s[2]=n:n&&(s[2]="("+s[2]+") and ("+n+")"),t.push(s))}},t}},41:function(e,t,n){var i,r,o={},s=(i=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===r&&(r=i.apply(this,arguments)),r}),a=function(e){var t={};return function(e,n){if("function"==typeof e)return e();if(void 0===t[e]){var i=function(e,t){return t?t.querySelector(e):document.querySelector(e)}.call(this,e,n);if(window.HTMLIFrameElement&&i instanceof window.HTMLIFrameElement)try{i=i.contentDocument.head}catch(e){i=null}t[e]=i}return t[e]}}(),l=null,c=0,u=[],f=n(42);function d(e,t){for(var n=0;n<e.length;n++){var i=e[n],r=o[i.id];if(r){r.refs++;for(var s=0;s<r.parts.length;s++)r.parts[s](i.parts[s]);for(;s<i.parts.length;s++)r.parts.push(g(i.parts[s],t))}else{var a=[];for(s=0;s<i.parts.length;s++)a.push(g(i.parts[s],t));o[i.id]={id:i.id,refs:1,parts:a}}}}function p(e,t){for(var n=[],i={},r=0;r<e.length;r++){var o=e[r],s=t.base?o[0]+t.base:o[0],a={css:o[1],media:o[2],sourceMap:o[3]};i[s]?i[s].parts.push(a):n.push(i[s]={id:s,parts:[a]})}return n}function h(e,t){var n=a(e.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var i=u[u.length-1];if("top"===e.insertAt)i?i.nextSibling?n.insertBefore(t,i.nextSibling):n.appendChild(t):n.insertBefore(t,n.firstChild),u.push(t);else if("bottom"===e.insertAt)n.appendChild(t);else{if("object"!=typeof e.insertAt||!e.insertAt.before)throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");var r=a(e.insertAt.before,n);n.insertBefore(t,r)}}function v(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e);var t=u.indexOf(e);t>=0&&u.splice(t,1)}function m(e){var t=document.createElement("style");if(void 0===e.attrs.type&&(e.attrs.type="text/css"),void 0===e.attrs.nonce){var i=function(){0;return n.nc}();i&&(e.attrs.nonce=i)}return b(t,e.attrs),h(e,t),t}function b(e,t){Object.keys(t).forEach(function(n){e.setAttribute(n,t[n])})}function g(e,t){var n,i,r,o;if(t.transform&&e.css){if(!(o="function"==typeof t.transform?t.transform(e.css):t.transform.default(e.css)))return function(){};e.css=o}if(t.singleton){var s=c++;n=l||(l=m(t)),i=x.bind(null,n,s,!1),r=x.bind(null,n,s,!0)}else e.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=function(e){var t=document.createElement("link");return void 0===e.attrs.type&&(e.attrs.type="text/css"),e.attrs.rel="stylesheet",b(t,e.attrs),h(e,t),t}(t),i=function(e,t,n){var i=n.css,r=n.sourceMap,o=void 0===t.convertToAbsoluteUrls&&r;(t.convertToAbsoluteUrls||o)&&(i=f(i));r&&(i+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(r))))+" */");var s=new Blob([i],{type:"text/css"}),a=e.href;e.href=URL.createObjectURL(s),a&&URL.revokeObjectURL(a)}.bind(null,n,t),r=function(){v(n),n.href&&URL.revokeObjectURL(n.href)}):(n=m(t),i=function(e,t){var n=t.css,i=t.media;i&&e.setAttribute("media",i);if(e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}.bind(null,n),r=function(){v(n)});return i(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;i(e=t)}else r()}}e.exports=function(e,t){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(t=t||{}).attrs="object"==typeof t.attrs?t.attrs:{},t.singleton||"boolean"==typeof t.singleton||(t.singleton=s()),t.insertInto||(t.insertInto="head"),t.insertAt||(t.insertAt="bottom");var n=p(e,t);return d(n,t),function(e){for(var i=[],r=0;r<n.length;r++){var s=n[r];(a=o[s.id]).refs--,i.push(a)}e&&d(p(e,t),t);for(r=0;r<i.length;r++){var a;if(0===(a=i[r]).refs){for(var l=0;l<a.parts.length;l++)a.parts[l]();delete o[a.id]}}}};var _,y=(_=[],function(e,t){return _[e]=t,_.filter(Boolean).join("\n")});function x(e,t,n,i){var r=n?"":i.css;if(e.styleSheet)e.styleSheet.cssText=y(t,r);else{var o=document.createTextNode(r),s=e.childNodes;s[t]&&e.removeChild(s[t]),s.length?e.insertBefore(o,s[t]):e.appendChild(o)}}},42:function(e,t){e.exports=function(e){var t="undefined"!=typeof window&&window.location;if(!t)throw new Error("fixUrls requires window.location");if(!e||"string"!=typeof e)return e;var n=t.protocol+"//"+t.host,i=n+t.pathname.replace(/\/[^\/]*$/,"/");return e.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,function(e,t){var r,o=t.trim().replace(/^"(.*)"$/,function(e,t){return t}).replace(/^'(.*)'$/,function(e,t){return t});return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(o)?e:(r=0===o.indexOf("//")?o:0===o.indexOf("/")?n+o:i+o.replace(/^\.\//,""),"url("+JSON.stringify(r)+")")})}},43:function(e,t,n){e.exports=n(46)},44:function(e,t,n){"use strict";var i=n(2);n.n(i).a},45:function(e,t,n){(e.exports=n(40)(!1)).push([e.i,"\nh2 {\n  font-size: 30px;\n  margin-top: 30px;\n  padding-top: 10px;\n  border-top: 1px dotted;\n}\n.checkbox-precision {\n  margin-top: 10px;\n  margin-left: 20px;\n}\n.field-description {\n  color: #888;\n  font-style: italic;\n}\n.final-thought-precision {\n  margin-top: 10px;\n}\n.card .card-body .submit-wrapper {\n  margin-top: 30px;\n}\n",""])},46:function(e,t,n){"use strict";n.r(t);var i={mixins:[{data:function(){return{fields:{},errors:{},success:!1,loaded:!0,action:""}},methods:{submit:function(){var e=this;this.loaded&&(this.loaded=!1,this.success=!1,this.errors={},axios.post(this.action,this.fields).then(function(t){e.loaded=!0,e.success=!0}).catch(function(t){e.loaded=!0,422===t.response.status&&(e.errors=t.response.data.errors||{})}))}}}],data:function(){var e=[{id:"notAutonomous",label:"Le jeu n'est pas autonome"},{id:"notLaunchable",label:"Impossible de lancer le jeu"},{id:"blockingBug",label:"Bug bloquant inévitable"},{id:"severalBugs",label:"Présence abusive de bugs non bloquants"},{id:"spellingMistakes",label:"Nombre abusif de fautes d'orthographe"},{id:"tooHard",label:"Difficulté abusive/mal calibrée",fieldDescription:"Nombre de game over injuste par heure de jeu, mauvaise maniabilité, explications manquantes..."},{id:"tooShort",label:"Jeu trop court",fieldDescription:"La totalité du jeu est observable en moins de 30 minutes"},{id:"unplayableAlone",label:"Impossible d'apprécier seul la majeure partie du jeu",fieldDescription:"Le multijoueur est nécessaire"}],t={};return e.forEach(function(e){t[e.id]={activated:!1,explanation:null}}),{action:"/qcm",questions:e,fields:{gameId:5,questionnaire:t,finalThought:null}}}};n(44);var r=function(e,t,n,i,r,o,s,a){var l,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=n,c._compiled=!0),i&&(c.functional=!0),o&&(c._scopeId="data-v-"+o),s?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),r&&r.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(s)},c._ssrRegister=l):r&&(l=a?function(){r.call(this,this.$root.$options.shadowRoot)}:r),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(e,t){return l.call(t),u(e,t)}}else{var f=c.beforeCreate;c.beforeCreate=f?[].concat(f,l):[l]}return{exports:e,options:c}}(i,function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("form",{attrs:{method:"POST"},on:{submit:function(t){return t.preventDefault(),e.submit(t)}}},[n("h2",[e._v("Check-up")]),e._v(" "),e._l(e.questions,function(t){return n("div",{key:t.label,staticClass:"form-group row"},[n("div",{staticClass:"col-sm-12"},[n("div",{staticClass:"form-check"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.fields.questionnaire[t.id].activated,expression:"fields.questionnaire[question.id].activated"}],staticClass:"form-check-input",attrs:{type:"checkbox",name:"question.id",id:t.id},domProps:{checked:Array.isArray(e.fields.questionnaire[t.id].activated)?e._i(e.fields.questionnaire[t.id].activated,null)>-1:e.fields.questionnaire[t.id].activated},on:{change:function(n){var i=e.fields.questionnaire[t.id].activated,r=n.target,o=!!r.checked;if(Array.isArray(i)){var s=e._i(i,null);r.checked?s<0&&e.$set(e.fields.questionnaire[t.id],"activated",i.concat([null])):s>-1&&e.$set(e.fields.questionnaire[t.id],"activated",i.slice(0,s).concat(i.slice(s+1)))}else e.$set(e.fields.questionnaire[t.id],"activated",o)}}}),e._v(" "),n("label",{staticClass:"form-check-label",attrs:{for:t.id}},[e._v("\n            "+e._s(t.label)+"\n            "),t.fieldDescription?n("span",{staticClass:"field-description"},[e._v("("+e._s(t.fieldDescription)+")")]):e._e()]),e._v(" "),e.errors&&e.errors[t.id]?n("div",{staticClass:"text-danger"},[e._v(e._s(e.errors[t.label][0]))]):e._e()]),e._v(" "),e.fields.questionnaire[t.id].activated?n("div",{staticClass:"checkbox-precision"},[n("label",{attrs:{for:"explanation-"+t.id}},[e._v("Précisions")]),e._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:e.fields.questionnaire[t.id].explanation,expression:"fields.questionnaire[question.id].explanation"}],staticStyle:{width:"100%"},attrs:{type:"text",name:"explanation-"+t.id,id:"explanation-"+t.id},domProps:{value:e.fields.questionnaire[t.id].explanation},on:{input:function(n){n.target.composing||e.$set(e.fields.questionnaire[t.id],"explanation",n.target.value)}}}),e._v(" "),e.errors&&e.errors[t.id+"Explanation"]?n("div",{staticClass:"text-danger"},[e._v(e._s(e.errors[t.id+"Explanation"][0]))]):e._e()]):e._e()])])}),e._v(" "),n("h2",[e._v("Verdict")]),e._v(" "),n("div",{staticClass:"form-group row"},[n("div",{staticClass:"col-sm-12"},[n("div",{staticClass:"form-check form-check-inline"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.fields.finalThought,expression:"fields.finalThought"}],staticClass:"form-check-input",attrs:{type:"radio",name:"finalThought",id:"finalThought-ok"},domProps:{value:!0,checked:e._q(e.fields.finalThought,!0)},on:{change:function(t){e.$set(e.fields,"finalThought",!0)}}}),e._v(" "),n("label",{staticClass:"form-check-label",attrs:{for:"finalThought-ok"}},[e._v("Conforme")])]),e._v(" "),n("div",{staticClass:"form-check form-check-inline"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.fields.finalThought,expression:"fields.finalThought"}],staticClass:"form-check-input",attrs:{type:"radio",name:"finalThought",id:"finalThought-not-ok"},domProps:{value:!1,checked:e._q(e.fields.finalThought,!1)},on:{change:function(t){e.$set(e.fields,"finalThought",!1)}}}),e._v(" "),n("label",{staticClass:"form-check-label",attrs:{for:"finalThought-not-ok"}},[e._v("Non conforme")])]),e._v(" "),e.errors&&e.errors.finalThought?n("div",{staticClass:"text-danger"},[e._v(e._s(e.errors.finalThought[0]))]):e._e(),e._v(" "),"false"===e.fields.finalThought?n("div",{staticClass:"final-thought-precision"},[n("label",{attrs:{for:"finalThoughtPrecision"}},[e._v("Précisions")]),e._v(" "),n("textarea",{directives:[{name:"model",rawName:"v-model",value:e.fields.finalThoughtPrecision,expression:"fields.finalThoughtPrecision"}],staticClass:"form-control",attrs:{name:"finalThoughtPrecision",id:"finalThoughtPrecision"},domProps:{value:e.fields.finalThoughtPrecision},on:{input:function(t){t.target.composing||e.$set(e.fields,"finalThoughtPrecision",t.target.value)}}}),e._v(" "),e.errors&&e.errors.finalThoughtPrecision?n("div",{staticClass:"text-danger"},[e._v(e._s(e.errors.finalThoughtPrecision[0]))]):e._e()]):e._e()])]),e._v(" "),e._m(0)],2)},[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"form-group row submit-wrapper"},[t("div",{staticClass:"col-md-12 text-center"},[t("button",{staticClass:"btn btn-primary mb-0",attrs:{type:"submit"}},[this._v("Envoyer")])])])}],!1,null,null,null);r.options.__file="PreTestsForm.vue";var o=r.exports;new Vue({el:"#pre-tests-form",components:{PreTestsForm:o}})}});