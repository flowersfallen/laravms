(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-bf512124"],{"77b7":function(t,e,o){"use strict";o.r(e);var s=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("uploader",{staticClass:"uploader-example",attrs:{options:t.options},on:{"file-success":t.fileSuccess}},[o("uploader-unsupport"),o("uploader-drop",[o("uploader-btn",{attrs:{attrs:t.attrs}},[t._v("选择视频")])],1),o("uploader-list")],1)},n=[],a=o("a18c"),u={data:function(){return{options:{target:"/admin/video/uploadVideo",testChunks:!1,query:{token:window.sessionStorage.getItem("token")}},attrs:{accept:"video/*"}}},methods:{fileSuccess:function(){var t=arguments[2],e=JSON.parse(t);50012===e.code&&a["a"].push("/login")}}},r=u,c=(o("98f7"),o("2877")),i=Object(c["a"])(r,s,n,!1,null,null,null);e["default"]=i.exports},"98f7":function(t,e,o){"use strict";o("a68c")},a68c:function(t,e,o){}}]);