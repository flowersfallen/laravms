(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-37c54721"],{"1ee4":function(t,a,s){},3332:function(t,a,s){},9406:function(t,a,s){"use strict";s.r(a);var e=function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"dashboard-container"},[s("panel-group",{attrs:{props:t.props}})],1)},n=[],i=function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",[s("el-row",{staticClass:"panel-group",attrs:{gutter:40}},[s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 上传数 ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[0][0],duration:3e3}}),t._v(" 个 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 切片数 ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[0][1],duration:3e3}}),t._v(" 个 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 合并数 ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[0][2],duration:3e3}}),t._v(" 个 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 总数 ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[0][3],duration:3e3}}),t._v(" 个 ")],1)])])],1),s("el-row",{staticClass:"panel-group",attrs:{gutter:40}},[s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 上传时长 "+t._s(t._f("formatsec")(t.props[1][0]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[1][0],duration:3e3}}),t._v(" 秒 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 切片时长 "+t._s(t._f("formatsec")(t.props[1][1]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[1][1],duration:3e3}}),t._v(" 秒 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 合并时长 "+t._s(t._f("formatsec")(t.props[1][2]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[1][2],duration:3e3}}),t._v(" 秒 ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 总时长 "+t._s(t._f("formatsec")(t.props[1][3]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[1][3],duration:3e3}}),t._v(" 秒 ")],1)])])],1),s("el-row",{staticClass:"panel-group",attrs:{gutter:40}},[s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 上传大小 "+t._s(t._f("formatbytes")(t.props[2][0]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[2][0],duration:3e3}}),t._v(" B ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 切片大小 "+t._s(t._f("formatbytes")(t.props[2][1]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[2][1],duration:3e3}}),t._v(" B ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 合并大小 "+t._s(t._f("formatbytes")(t.props[2][2]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[2][2],duration:3e3}}),t._v(" B ")],1)])]),s("el-col",{staticClass:"card-panel-col",attrs:{xs:12,sm:12,lg:6}},[s("div",{staticClass:"card-panel"},[s("div",{staticClass:"card-panel-description"},[s("div",{staticClass:"card-panel-text"},[t._v(" 总大小 "+t._s(t._f("formatbytes")(t.props[2][3]))+" ")]),s("count-to",{staticClass:"card-panel-num",attrs:{"start-val":0,"end-val":t.props[2][3],duration:3e3}}),t._v(" B ")],1)])])],1)],1)},r=[],l=s("ec1b"),o=s.n(l),c={components:{CountTo:o.a},filters:{formatbytes:function(t){if(0===t)return"0 B";var a=1e3,s=["B","KB","MB","GB","TB","PB","EB","ZB","YB"],e=Math.floor(Math.log(t)/Math.log(a));return(t/Math.pow(a,e)).toPrecision(3)+" "+s[e]},formatsec:function(t){var a=parseInt(t),s=parseInt(a/3600),e=parseInt(a%3600/60),n=parseInt(a%3600%60),i=s>9?s:"0"+s,r=e>9?e:"0"+e,l=n>9?n:"0"+n;return i+":"+r+":"+l}},props:{props:{type:Array,default:function(){return[[0,0,0,0],[0,0,0,0],[0,0,0,0]]}}}},u=c,d=(s("bf33"),s("2877")),p=Object(d["a"])(u,i,r,!1,null,"79c87678",null),m=p.exports,f=s("c24f"),h={name:"Dashboard",components:{PanelGroup:m},data:function(){return{props:[[0,0,0,0],[0,0,0,0],[0,0,0,0]]}},created:function(){this.getData()},methods:{getData:function(){var t=this;Object(f["e"])().then((function(a){t.props=a.data.items}))}}},v=h,C=(s("adb1"),Object(d["a"])(v,e,n,!1,null,"009b5d42",null));a["default"]=C.exports},adb1:function(t,a,s){"use strict";s("3332")},bf33:function(t,a,s){"use strict";s("1ee4")},ec1b:function(t,a,s){!function(a,s){t.exports=s()}(0,(function(){return function(t){function a(e){if(s[e])return s[e].exports;var n=s[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,a),n.l=!0,n.exports}var s={};return a.m=t,a.c=s,a.i=function(t){return t},a.d=function(t,s,e){a.o(t,s)||Object.defineProperty(t,s,{configurable:!1,enumerable:!0,get:e})},a.n=function(t){var s=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(s,"a",s),s},a.o=function(t,a){return Object.prototype.hasOwnProperty.call(t,a)},a.p="/dist/",a(a.s=2)}([function(t,a,s){var e=s(4)(s(1),s(5),null,null);t.exports=e.exports},function(t,a,s){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var e=s(3);a.default={props:{startVal:{type:Number,required:!1,default:0},endVal:{type:Number,required:!1,default:2017},duration:{type:Number,required:!1,default:3e3},autoplay:{type:Boolean,required:!1,default:!0},decimals:{type:Number,required:!1,default:0,validator:function(t){return t>=0}},decimal:{type:String,required:!1,default:"."},separator:{type:String,required:!1,default:","},prefix:{type:String,required:!1,default:""},suffix:{type:String,required:!1,default:""},useEasing:{type:Boolean,required:!1,default:!0},easingFn:{type:Function,default:function(t,a,s,e){return s*(1-Math.pow(2,-10*t/e))*1024/1023+a}}},data:function(){return{localStartVal:this.startVal,displayValue:this.formatNumber(this.startVal),printVal:null,paused:!1,localDuration:this.duration,startTime:null,timestamp:null,remaining:null,rAF:null}},computed:{countDown:function(){return this.startVal>this.endVal}},watch:{startVal:function(){this.autoplay&&this.start()},endVal:function(){this.autoplay&&this.start()}},mounted:function(){this.autoplay&&this.start(),this.$emit("mountedCallback")},methods:{start:function(){this.localStartVal=this.startVal,this.startTime=null,this.localDuration=this.duration,this.paused=!1,this.rAF=(0,e.requestAnimationFrame)(this.count)},pauseResume:function(){this.paused?(this.resume(),this.paused=!1):(this.pause(),this.paused=!0)},pause:function(){(0,e.cancelAnimationFrame)(this.rAF)},resume:function(){this.startTime=null,this.localDuration=+this.remaining,this.localStartVal=+this.printVal,(0,e.requestAnimationFrame)(this.count)},reset:function(){this.startTime=null,(0,e.cancelAnimationFrame)(this.rAF),this.displayValue=this.formatNumber(this.startVal)},count:function(t){this.startTime||(this.startTime=t),this.timestamp=t;var a=t-this.startTime;this.remaining=this.localDuration-a,this.useEasing?this.countDown?this.printVal=this.localStartVal-this.easingFn(a,0,this.localStartVal-this.endVal,this.localDuration):this.printVal=this.easingFn(a,this.localStartVal,this.endVal-this.localStartVal,this.localDuration):this.countDown?this.printVal=this.localStartVal-(this.localStartVal-this.endVal)*(a/this.localDuration):this.printVal=this.localStartVal+(this.localStartVal-this.startVal)*(a/this.localDuration),this.countDown?this.printVal=this.printVal<this.endVal?this.endVal:this.printVal:this.printVal=this.printVal>this.endVal?this.endVal:this.printVal,this.displayValue=this.formatNumber(this.printVal),a<this.localDuration?this.rAF=(0,e.requestAnimationFrame)(this.count):this.$emit("callback")},isNumber:function(t){return!isNaN(parseFloat(t))},formatNumber:function(t){t=t.toFixed(this.decimals),t+="";var a=t.split("."),s=a[0],e=a.length>1?this.decimal+a[1]:"",n=/(\d+)(\d{3})/;if(this.separator&&!this.isNumber(this.separator))for(;n.test(s);)s=s.replace(n,"$1"+this.separator+"$2");return this.prefix+s+e+this.suffix}},destroyed:function(){(0,e.cancelAnimationFrame)(this.rAF)}}},function(t,a,s){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var e=s(0),n=function(t){return t&&t.__esModule?t:{default:t}}(e);a.default=n.default,"undefined"!=typeof window&&window.Vue&&window.Vue.component("count-to",n.default)},function(t,a,s){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var e=0,n="webkit moz ms o".split(" "),i=void 0,r=void 0;if("undefined"==typeof window)a.requestAnimationFrame=i=function(){},a.cancelAnimationFrame=r=function(){};else{a.requestAnimationFrame=i=window.requestAnimationFrame,a.cancelAnimationFrame=r=window.cancelAnimationFrame;for(var l=void 0,o=0;o<n.length&&(!i||!r);o++)l=n[o],a.requestAnimationFrame=i=i||window[l+"RequestAnimationFrame"],a.cancelAnimationFrame=r=r||window[l+"CancelAnimationFrame"]||window[l+"CancelRequestAnimationFrame"];i&&r||(a.requestAnimationFrame=i=function(t){var a=(new Date).getTime(),s=Math.max(0,16-(a-e)),n=window.setTimeout((function(){t(a+s)}),s);return e=a+s,n},a.cancelAnimationFrame=r=function(t){window.clearTimeout(t)})}a.requestAnimationFrame=i,a.cancelAnimationFrame=r},function(t,a){t.exports=function(t,a,s,e){var n,i=t=t||{},r=typeof t.default;"object"!==r&&"function"!==r||(n=t,i=t.default);var l="function"==typeof i?i.options:i;if(a&&(l.render=a.render,l.staticRenderFns=a.staticRenderFns),s&&(l._scopeId=s),e){var o=Object.create(l.computed||null);Object.keys(e).forEach((function(t){var a=e[t];o[t]=function(){return a}})),l.computed=o}return{esModule:n,exports:i,options:l}}},function(t,a){t.exports={render:function(){var t=this,a=t.$createElement;return(t._self._c||a)("span",[t._v("\n  "+t._s(t.displayValue)+"\n")])},staticRenderFns:[]}}])}))}}]);