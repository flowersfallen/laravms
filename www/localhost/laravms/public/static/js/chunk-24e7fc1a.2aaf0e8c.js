(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-24e7fc1a"],{"093a":function(t,e,n){"use strict";n.d(e,"f",(function(){return i})),n.d(e,"d",(function(){return r})),n.d(e,"e",(function(){return o})),n.d(e,"a",(function(){return c})),n.d(e,"b",(function(){return u})),n.d(e,"g",(function(){return l})),n.d(e,"c",(function(){return s})),n.d(e,"h",(function(){return d}));var a=n("b775");function i(t){return Object(a["a"])({url:"/admin/video/listVideo",method:"get",params:t})}function r(t){return Object(a["a"])({url:"/admin/video/listClip",method:"get",params:t})}function o(t){return Object(a["a"])({url:"/admin/video/listCombine",method:"get",params:t})}function c(t){return Object(a["a"])({url:"/admin/video/clipVideo",method:"post",data:t})}function u(t){return Object(a["a"])({url:"/admin/video/combineClip",method:"get",params:t})}function l(t){return Object(a["a"])({url:"/admin/video/setShare",method:"get",params:t})}function s(t){return Object(a["a"])({url:"/admin/video/delItem",method:"get",params:t})}function d(t){return Object(a["a"])({url:"/admin/video/updateVideo",method:"get",params:t})}},"0ccb":function(t,e,n){var a=n("50c4"),i=n("1148"),r=n("1d80"),o=Math.ceil,c=function(t){return function(e,n,c){var u,l,s=String(r(e)),d=s.length,f=void 0===c?" ":String(c),p=a(n);return p<=d||""==f?s:(u=p-d,l=i.call(f,o(u/f.length)),l.length>u&&(l=l.slice(0,u)),t?s+l:l+s)}};t.exports={start:c(!1),end:c(!0)}},1:function(t,e){},1148:function(t,e,n){"use strict";var a=n("a691"),i=n("1d80");t.exports="".repeat||function(t){var e=String(i(this)),n="",r=a(t);if(r<0||r==1/0)throw RangeError("Wrong number of repetitions");for(;r>0;(r>>>=1)&&(e+=e))1&r&&(n+=e);return n}},"14c3":function(t,e,n){var a=n("c6b6"),i=n("9263");t.exports=function(t,e){var n=t.exec;if("function"===typeof n){var r=n.call(t,e);if("object"!==typeof r)throw TypeError("RegExp exec method returned something other than an Object or null");return r}if("RegExp"!==a(t))throw TypeError("RegExp#exec called on incompatible receiver");return i.call(t,e)}},"1d79":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-input",{staticStyle:{"margin-bottom":"30px"},attrs:{placeholder:"关键词，多个用空格隔开",clearable:""},on:{change:t.searchValueChange},model:{value:t.searchValue,callback:function(e){t.searchValue=e},expression:"searchValue"}}),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",border:"",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"ID",width:"95",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.$index+1)+" ")]}}])}),n("el-table-column",{attrs:{label:"缩略图",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("img",{staticStyle:{width:"80px",height:"40px"},attrs:{src:e.row.thumb},on:{click:function(n){return t.handleUpdate(e.row)}}})]}}])}),n("el-table-column",{attrs:{label:"标题"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",{on:{click:function(n){return t.handleUpdate(e.row)}}},[t._v(t._s(e.row.title))])]}}])}),n("el-table-column",{attrs:{label:"标签"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.tags)+" ")]}}])}),n("el-table-column",{attrs:{label:"时长",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.duration)+" ")]}}])}),n("el-table-column",{attrs:{label:"宽高",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.width)+" * "+t._s(e.row.height)+" ")]}}])}),n("el-table-column",{attrs:{label:"创建人",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(e.row.admin_name))])]}}])}),n("el-table-column",{attrs:{label:"创建时间",width:"200",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(e.row.created_at))])]}}])}),n("el-table-column",{attrs:{label:"是否分享",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-switch",{attrs:{"active-color":"#0d5eff"},on:{change:function(n){return t.setShare(e.row.id,e.row.switchValue?1:0)}},model:{value:e.row.switchValue,callback:function(n){t.$set(e.row,"switchValue",n)},expression:"scope.row.switchValue"}})]}}])}),n("el-table-column",{attrs:{label:"操作",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",{staticClass:"operate",on:{click:function(n){return t.updateItem(e.row)}}},[t._v("编辑")]),t._v("|"),n("span",{staticClass:"operate",on:{click:function(n){return t.delItem(e.row.id)}}},[t._v("删除")])]}}])})],1),n("pagination",{directives:[{name:"show",rawName:"v-show",value:t.total>0,expression:"total>0"}],attrs:{total:t.total,page:t.listQuery.page,limit:t.listQuery.limit},on:{"update:page":function(e){return t.$set(t.listQuery,"page",e)},"update:limit":function(e){return t.$set(t.listQuery,"limit",e)},pagination:t.fetchData}}),n("el-dialog",{attrs:{title:t.textMap[t.dialogStatus],visible:t.dialogFormVisible},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[n("video-origin",{ref:"videoOrigin",attrs:{options:t.videoOptions}})],1),n("el-dialog",{attrs:{visible:t.dialogVisible,title:"修改",width:"700px"},on:{"update:visible":function(e){t.dialogVisible=e},closed:t.closeDialog}},[n("el-form",{ref:"addAccountForm",attrs:{model:t.addAccountForm,rules:t.addAccountFormRules,"status-icon":"","label-width":"100px"}},[n("el-form-item",{attrs:{label:"标题:",prop:"title"}},[n("el-input",{model:{value:t.addAccountForm.title,callback:function(e){t.$set(t.addAccountForm,"title",e)},expression:"addAccountForm.title"}})],1),n("el-form-item",{attrs:{label:"标签",prop:"tags"}},[n("el-input",{model:{value:t.addAccountForm.tags,callback:function(e){t.$set(t.addAccountForm,"tags",e)},expression:"addAccountForm.tags"}})],1),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("修改")]),n("el-button",{on:{click:function(e){t.dialogVisible=!1}}},[t._v("取消")])],1)],1)],1)],1)},i=[],r=n("ed08"),o=n("093a"),c=n("333d"),u=n("dd73"),l=(n("fda2"),{title:"",tags:""}),s={components:{Pagination:c["a"],VideoOrigin:u["a"]},filters:{statusFilter:function(t){var e={published:"success",draft:"gray",deleted:"danger"};return e[t]}},data:function(){var t=function(t,e,n){e?n():n(new Error("请输入标题"))},e=function(t,e,n){e?n():n(new Error("请输入标签"))};return{list:null,total:0,listLoading:!0,searchValue:"",listQuery:{page:1,limit:10},temp:{id:void 0,title:"",video:""},dialogFormVisible:!1,dialogStatus:"",textMap:{update:"预览",create:"Create"},videoOptions:{autoplay:!1,controls:!0,sources:[{src:""}],controlBar:{playToggle:!1,volumePanel:{inline:!1},remainingTimeDisplay:!1,fullscreenToggle:!1,pictureInPictureToggle:!1}},dialogVisible:!1,addAccountForm:Object(r["a"])(l),addAccountFormRules:{title:[{required:!0,validator:t,trigger:"blur"}],tags:[{required:!0,validator:e,trigger:"blur"}]}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={keyword:this.searchValue,page:this.listQuery.page,pageSize:this.listQuery.limit};Object(o["d"])(e).then((function(e){t.list=e.data.items,t.total=e.data.total,$.each(t.list,(function(t,e){1===e.is_sharing?e.switchValue=!0:e.switchValue=!1})),t.listLoading=!1}))},searchValueChange:function(){this.fetchData()},handleUpdate:function(t){var e=this;this.temp=Object.assign({},t),this.dialogStatus="update",this.dialogFormVisible=!0,this.videoOptions.sources[0].src=t.video,this.$nextTick((function(){e.$refs.videoOrigin.openIt()}))},setShare:function(t,e){var n=this,a={id:t,is_sharing:e,type:2};Object(o["g"])(a).then((function(t){t.state?(n.$message.success("请求成功"),n.fetchData()):n.$message.error("请求失败")})).catch((function(t){console.log(t)}))},delItem:function(t){var e=this,n={ids:t,type:2};Object(o["c"])(n).then((function(t){t.state?(e.$message.success("请求成功"),e.fetchData()):e.$message.error("请求失败")})).catch((function(t){console.log(t)}))},updateItem:function(t){for(var e in Object.assign(this.addAccountForm,{id:""}),this.addAccountForm)this.addAccountForm[e]=t[e];this.dialogVisible=!0},onSubmit:function(){var t=this;this.$refs["addAccountForm"].validate((function(e){if(e){var n={};for(var a in t.addAccountForm)-1===[null,void 0].indexOf(t.addAccountForm[a])&&(n[a]=t.addAccountForm[a]);n["type"]=2,Object(o["h"])(n).then((function(e){e.state&&(t.dialogVisible=!1,t.$message.success("修改成功"),t.fetchData())}))}else t.$message.error("表单信息填写错误！")}))},closeDialog:function(){this.addAccountForm=Object(r["a"])(l),this.$refs.addAccountForm.clearValidate()}}},d=s,f=(n("f973"),n("2877")),p=Object(f["a"])(d,a,i,!1,null,null,null);e["default"]=p.exports},"25f0":function(t,e,n){"use strict";var a=n("6eeb"),i=n("825a"),r=n("d039"),o=n("ad6d"),c="toString",u=RegExp.prototype,l=u[c],s=r((function(){return"/a/b"!=l.call({source:"a",flags:"b"})})),d=l.name!=c;(s||d)&&a(RegExp.prototype,c,(function(){var t=i(this),e=String(t.source),n=t.flags,a=String(void 0===n&&t instanceof RegExp&&!("flags"in u)?o.call(t):n);return"/"+e+"/"+a}),{unsafe:!0})},"2c3e":function(t,e,n){var a=n("83ab"),i=n("9f7f").UNSUPPORTED_Y,r=n("9bf2").f,o=n("69f3").get,c=RegExp.prototype;a&&i&&r(RegExp.prototype,"sticky",{configurable:!0,get:function(){if(this!==c){if(this instanceof RegExp)return!!o(this).sticky;throw TypeError("Incompatible receiver, RegExp required")}}})},3016:function(t,e,n){},"333d":function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"pagination-container",class:{hidden:t.hidden}},[n("el-pagination",t._b({attrs:{background:t.background,"current-page":t.currentPage,"page-size":t.pageSize,layout:t.layout,"page-sizes":t.pageSizes,total:t.total},on:{"update:currentPage":function(e){t.currentPage=e},"update:current-page":function(e){t.currentPage=e},"update:pageSize":function(e){t.pageSize=e},"update:page-size":function(e){t.pageSize=e},"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}},"el-pagination",t.$attrs,!1))],1)},i=[];n("a9e3");Math.easeInOutQuad=function(t,e,n,a){return t/=a/2,t<1?n/2*t*t+e:(t--,-n/2*(t*(t-2)-1)+e)};var r=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||function(t){window.setTimeout(t,1e3/60)}}();function o(t){document.documentElement.scrollTop=t,document.body.parentNode.scrollTop=t,document.body.scrollTop=t}function c(){return document.documentElement.scrollTop||document.body.parentNode.scrollTop||document.body.scrollTop}function u(t,e,n){var a=c(),i=t-a,u=20,l=0;e="undefined"===typeof e?500:e;var s=function t(){l+=u;var c=Math.easeInOutQuad(l,a,i,e);o(c),l<e?r(t):n&&"function"===typeof n&&n()};s()}var l={name:"Pagination",props:{total:{required:!0,type:Number},page:{type:Number,default:1},limit:{type:Number,default:20},pageSizes:{type:Array,default:function(){return[10,20,30,50]}},layout:{type:String,default:"total, sizes, prev, pager, next, jumper"},background:{type:Boolean,default:!0},autoScroll:{type:Boolean,default:!0},hidden:{type:Boolean,default:!1}},computed:{currentPage:{get:function(){return this.page},set:function(t){this.$emit("update:page",t)}},pageSize:{get:function(){return this.limit},set:function(t){this.$emit("update:limit",t)}}},methods:{handleSizeChange:function(t){this.$emit("pagination",{page:this.currentPage,limit:t}),this.autoScroll&&u(0,800)},handleCurrentChange:function(t){this.$emit("pagination",{page:t,limit:this.pageSize}),this.autoScroll&&u(0,800)}}},s=l,d=(n("5660"),n("2877")),f=Object(d["a"])(s,a,i,!1,null,"6af373ef",null);e["a"]=f.exports},"4d63":function(t,e,n){var a=n("83ab"),i=n("da84"),r=n("94ca"),o=n("7156"),c=n("9bf2").f,u=n("241c").f,l=n("44e7"),s=n("ad6d"),d=n("9f7f"),f=n("6eeb"),p=n("d039"),g=n("69f3").set,h=n("2626"),m=n("b622"),v=m("match"),b=i.RegExp,y=b.prototype,w=/a/g,S=/a/g,x=new b(w)!==w,_=d.UNSUPPORTED_Y,E=a&&r("RegExp",!x||_||p((function(){return S[v]=!1,b(w)!=w||b(S)==S||"/a/i"!=b(w,"i")})));if(E){var O=function(t,e){var n,a=this instanceof O,i=l(t),r=void 0===e;if(!a&&i&&t.constructor===O&&r)return t;x?i&&!r&&(t=t.source):t instanceof O&&(r&&(e=s.call(t)),t=t.source),_&&(n=!!e&&e.indexOf("y")>-1,n&&(e=e.replace(/y/g,"")));var c=o(x?new b(t,e):b(t,e),a?this:y,O);return _&&n&&g(c,{sticky:n}),c},$=function(t){t in O||c(O,t,{configurable:!0,get:function(){return b[t]},set:function(e){b[t]=e}})},k=u(b),A=0;while(k.length>A)$(k[A++]);y.constructor=O,O.prototype=y,f(i,"RegExp",O)}h("RegExp")},"4d90":function(t,e,n){"use strict";var a=n("23e7"),i=n("0ccb").start,r=n("9a0c");a({target:"String",proto:!0,forced:r},{padStart:function(t){return i(this,t,arguments.length>1?arguments[1]:void 0)}})},5319:function(t,e,n){"use strict";var a=n("d784"),i=n("825a"),r=n("7b0b"),o=n("50c4"),c=n("a691"),u=n("1d80"),l=n("8aa5"),s=n("14c3"),d=Math.max,f=Math.min,p=Math.floor,g=/\$([$&'`]|\d\d?|<[^>]*>)/g,h=/\$([$&'`]|\d\d?)/g,m=function(t){return void 0===t?t:String(t)};a("replace",2,(function(t,e,n,a){var v=a.REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE,b=a.REPLACE_KEEPS_$0,y=v?"$":"$0";return[function(n,a){var i=u(this),r=void 0==n?void 0:n[t];return void 0!==r?r.call(n,i,a):e.call(String(i),n,a)},function(t,a){if(!v&&b||"string"===typeof a&&-1===a.indexOf(y)){var r=n(e,t,this,a);if(r.done)return r.value}var u=i(t),p=String(this),g="function"===typeof a;g||(a=String(a));var h=u.global;if(h){var S=u.unicode;u.lastIndex=0}var x=[];while(1){var _=s(u,p);if(null===_)break;if(x.push(_),!h)break;var E=String(_[0]);""===E&&(u.lastIndex=l(p,o(u.lastIndex),S))}for(var O="",$=0,k=0;k<x.length;k++){_=x[k];for(var A=String(_[0]),R=d(f(c(_.index),p.length),0),j=[],F=1;F<_.length;F++)j.push(m(_[F]));var V=_.groups;if(g){var P=[A].concat(j,R,p);void 0!==V&&P.push(V);var T=String(a.apply(void 0,P))}else T=w(A,p,R,j,V,a);R>=$&&(O+=p.slice($,R)+T,$=R+A.length)}return O+p.slice($)}];function w(t,n,a,i,o,c){var u=a+t.length,l=i.length,s=h;return void 0!==o&&(o=r(o),s=g),e.call(c,s,(function(e,r){var c;switch(r.charAt(0)){case"$":return"$";case"&":return t;case"`":return n.slice(0,a);case"'":return n.slice(u);case"<":c=o[r.slice(1,-1)];break;default:var s=+r;if(0===s)return e;if(s>l){var d=p(s/10);return 0===d?e:d<=l?void 0===i[d-1]?r.charAt(1):i[d-1]+r.charAt(1):e}c=i[s-1]}return void 0===c?"":c}))}}))},5660:function(t,e,n){"use strict";n("7a30")},"7a30":function(t,e,n){},"8aa5":function(t,e,n){"use strict";var a=n("6547").charAt;t.exports=function(t,e,n){return e+(n?a(t,e).length:1)}},"9a0c":function(t,e,n){var a=n("342f");t.exports=/Version\/10\.\d+(\.\d+)?( Mobile\/\w+)? Safari\//.test(a)},d784:function(t,e,n){"use strict";n("ac1f");var a=n("6eeb"),i=n("d039"),r=n("b622"),o=n("9263"),c=n("9112"),u=r("species"),l=!i((function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")})),s=function(){return"$0"==="a".replace(/./,"$0")}(),d=r("replace"),f=function(){return!!/./[d]&&""===/./[d]("a","$0")}(),p=!i((function(){var t=/(?:)/,e=t.exec;t.exec=function(){return e.apply(this,arguments)};var n="ab".split(t);return 2!==n.length||"a"!==n[0]||"b"!==n[1]}));t.exports=function(t,e,n,d){var g=r(t),h=!i((function(){var e={};return e[g]=function(){return 7},7!=""[t](e)})),m=h&&!i((function(){var e=!1,n=/a/;return"split"===t&&(n={},n.constructor={},n.constructor[u]=function(){return n},n.flags="",n[g]=/./[g]),n.exec=function(){return e=!0,null},n[g](""),!e}));if(!h||!m||"replace"===t&&(!l||!s||f)||"split"===t&&!p){var v=/./[g],b=n(g,""[t],(function(t,e,n,a,i){return e.exec===o?h&&!i?{done:!0,value:v.call(e,n,a)}:{done:!0,value:t.call(n,e,a)}:{done:!1}}),{REPLACE_KEEPS_$0:s,REGEXP_REPLACE_SUBSTITUTES_UNDEFINED_CAPTURE:f}),y=b[0],w=b[1];a(String.prototype,t,y),a(RegExp.prototype,g,2==e?function(t,e){return w.call(t,this,e)}:function(t){return w.call(t,this)})}d&&c(RegExp.prototype[g],"sham",!0)}},dd73:function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("video",{ref:"videoOrigin",staticClass:"video-js"})])},i=[],r=n("f0e2"),o=n("2eee"),c={name:"VideoOrigin",props:{options:{type:Object,default:function(){return{}}}},data:function(){return{player:null}},mounted:function(){var t=this;r["a"].addLanguage("zh-CN",o),this.player=Object(r["a"])(this.$refs.videoOrigin,this.options,(function(){t.player.log("onPlayerReady",t)}))},beforeDestroy:function(){this.player&&this.player.dispose()},methods:{openIt:function(){this.player&&(this.player.reset(),this.player.src(this.options.sources[0].src),this.player.load())}}},u=c,l=n("2877"),s=Object(l["a"])(u,a,i,!1,null,null,null);e["a"]=s.exports},ed08:function(t,e,n){"use strict";n.d(e,"a",(function(){return i}));var a=n("53ca");n("ac1f"),n("00b4"),n("5319"),n("4d63"),n("2c3e"),n("25f0"),n("d3b7"),n("4d90"),n("159b"),n("b64b");function i(t){if(!t&&"object"!==Object(a["a"])(t))return console.log("深克隆失败"),t;var e=t.constructor===Array?[]:{};return Object.keys(t).forEach((function(n){t[n]&&"object"===Object(a["a"])(t[n])?e[n]=i(t[n]):e[n]=t[n]})),e}},f973:function(t,e,n){"use strict";n("3016")}}]);