(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-ed7e2394"],{"093a":function(t,e,n){"use strict";n.d(e,"f",(function(){return i})),n.d(e,"d",(function(){return r})),n.d(e,"e",(function(){return o})),n.d(e,"a",(function(){return u})),n.d(e,"b",(function(){return l})),n.d(e,"g",(function(){return c})),n.d(e,"c",(function(){return s})),n.d(e,"h",(function(){return d}));var a=n("b775");function i(t){return Object(a["a"])({url:"/admin/video/listVideo",method:"get",params:t})}function r(t){return Object(a["a"])({url:"/admin/video/listClip",method:"get",params:t})}function o(t){return Object(a["a"])({url:"/admin/video/listCombine",method:"get",params:t})}function u(t){return Object(a["a"])({url:"/admin/video/clipVideo",method:"post",data:t})}function l(t){return Object(a["a"])({url:"/admin/video/combineClip",method:"get",params:t})}function c(t){return Object(a["a"])({url:"/admin/video/setShare",method:"get",params:t})}function s(t){return Object(a["a"])({url:"/admin/video/delItem",method:"get",params:t})}function d(t){return Object(a["a"])({url:"/admin/video/updateVideo",method:"get",params:t})}},"333d":function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"pagination-container",class:{hidden:t.hidden}},[n("el-pagination",t._b({attrs:{background:t.background,"current-page":t.currentPage,"page-size":t.pageSize,layout:t.layout,"page-sizes":t.pageSizes,total:t.total},on:{"update:currentPage":function(e){t.currentPage=e},"update:current-page":function(e){t.currentPage=e},"update:pageSize":function(e){t.pageSize=e},"update:page-size":function(e){t.pageSize=e},"size-change":t.handleSizeChange,"current-change":t.handleCurrentChange}},"el-pagination",t.$attrs,!1))],1)},i=[];n("a9e3");Math.easeInOutQuad=function(t,e,n,a){return t/=a/2,t<1?n/2*t*t+e:(t--,-n/2*(t*(t-2)-1)+e)};var r=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||function(t){window.setTimeout(t,1e3/60)}}();function o(t){document.documentElement.scrollTop=t,document.body.parentNode.scrollTop=t,document.body.scrollTop=t}function u(){return document.documentElement.scrollTop||document.body.parentNode.scrollTop||document.body.scrollTop}function l(t,e,n){var a=u(),i=t-a,l=20,c=0;e="undefined"===typeof e?500:e;var s=function t(){c+=l;var u=Math.easeInOutQuad(c,a,i,e);o(u),c<e?r(t):n&&"function"===typeof n&&n()};s()}var c={name:"Pagination",props:{total:{required:!0,type:Number},page:{type:Number,default:1},limit:{type:Number,default:20},pageSizes:{type:Array,default:function(){return[10,20,30,50]}},layout:{type:String,default:"total, sizes, prev, pager, next, jumper"},background:{type:Boolean,default:!0},autoScroll:{type:Boolean,default:!0},hidden:{type:Boolean,default:!1}},computed:{currentPage:{get:function(){return this.page},set:function(t){this.$emit("update:page",t)}},pageSize:{get:function(){return this.limit},set:function(t){this.$emit("update:limit",t)}}},methods:{handleSizeChange:function(t){this.$emit("pagination",{page:this.currentPage,limit:t}),this.autoScroll&&l(0,800)},handleCurrentChange:function(t){this.$emit("pagination",{page:t,limit:this.pageSize}),this.autoScroll&&l(0,800)}}},s=c,d=(n("5660"),n("2877")),p=Object(d["a"])(s,a,i,!1,null,"6af373ef",null);e["a"]=p.exports},5660:function(t,e,n){"use strict";n("7a30")},"7a30":function(t,e,n){},ea9f:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"app-container"},[n("el-input",{staticStyle:{"margin-bottom":"30px"},attrs:{placeholder:"关键词，多个用空格隔开",clearable:""},on:{change:t.searchValueChange},model:{value:t.searchValue,callback:function(e){t.searchValue=e},expression:"searchValue"}}),n("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],attrs:{data:t.list,"element-loading-text":"Loading",border:"",fit:"","highlight-current-row":""}},[n("el-table-column",{attrs:{label:"ID",width:"95",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.$index+1)+" ")]}}])}),n("el-table-column",{attrs:{label:"缩略图",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("img",{staticStyle:{width:"80px",height:"40px"},attrs:{src:e.row.thumb},on:{click:function(n){return t.clipVideo(e.row)}}})]}}])}),n("el-table-column",{attrs:{label:"标题"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.title)+" ")]}}])}),n("el-table-column",{attrs:{label:"标签"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.tags)+" ")]}}])}),n("el-table-column",{attrs:{label:"时长",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.duration)+" ")]}}])}),n("el-table-column",{attrs:{label:"宽高",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v(" "+t._s(e.row.width)+" * "+t._s(e.row.height)+" ")]}}])}),n("el-table-column",{attrs:{label:"创建人",width:"110",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(e.row.admin_name))])]}}])}),n("el-table-column",{attrs:{label:"创建时间",width:"200",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("span",[t._v(t._s(e.row.created_at))])]}}])})],1),n("pagination",{directives:[{name:"show",rawName:"v-show",value:t.total>0,expression:"total>0"}],attrs:{total:t.total,page:t.listQuery.page,limit:t.listQuery.limit},on:{"update:page":function(e){return t.$set(t.listQuery,"page",e)},"update:limit":function(e){return t.$set(t.listQuery,"limit",e)},pagination:t.fetchData}})],1)},i=[],r=n("093a"),o=n("333d"),u={components:{Pagination:o["a"]},filters:{statusFilter:function(t){var e={published:"success",draft:"gray",deleted:"danger"};return e[t]}},data:function(){return{list:null,total:0,listLoading:!0,searchValue:"",listQuery:{page:1,limit:10}}},created:function(){this.fetchData()},methods:{fetchData:function(){var t=this;this.listLoading=!0;var e={keyword:this.searchValue,page:this.listQuery.page,pageSize:this.listQuery.limit,share:1};Object(r["f"])(e).then((function(e){t.list=e.data.items,t.total=e.data.total,t.listLoading=!1}))},searchValueChange:function(){this.fetchData()},clipVideo:function(t){this.$router.push({name:"ClipVideo",params:{url:t.video,title:t.title,upload_id:t.id}})}}},l=u,c=n("2877"),s=Object(c["a"])(l,a,i,!1,null,null,null);e["default"]=s.exports}}]);