webpackJsonp([7],{ZThV:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var l={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"activity-list page-list"},[a("el-form",{ref:"form",attrs:{model:e.form}},[a("el-row",[a("el-col",{attrs:{span:6}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},e._l(e.typeList,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1)],1),e._v(" "),a("el-col",{attrs:{span:6}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.status,callback:function(t){e.$set(e.form,"status",t)},expression:"form.status"}},e._l(e.statusList,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1)],1),e._v(" "),a("el-col",{attrs:{span:6}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-input",{attrs:{placeholder:"搜索活动名称或活动id"},model:{value:e.form.desc,callback:function(t){e.$set(e.form,"desc",t)},expression:"form.desc"}})],1)],1)],1)],1),e._v(" "),a("div",{staticClass:"flex1"},[a("Utable",{attrs:{data:e.list,cols:e.cols,actions:e.actions,pageNumber:e.pageState.pageNumber,pageSize:e.pageState.pageSize,total:e.pageState.total},on:{pageSize:e.onPageSize,pageNumber:e.onPageNumber}})],1)],1)},staticRenderFns:[]};var r=a("VU/8")({data:function(){return this.$createElement,{form:{type:"",status:"",desc:""},typeList:[],statusList:[],list:[],cols:[{label:"活动ID",prop:"id",align:"center"},{label:"活动名称",prop:"id",align:"center"},{label:"活动类型",prop:"id",align:"center"},{label:"创建时间",prop:"id",align:"center"},{label:"审批状态",prop:"id",align:"center"}],actions:[{render:function(e,t){return e("div",[e("el-button",{attrs:{type:"text"},on:{click:function(){}}},["查看"])])}}],pageState:{pageSize:10,pageNumber:1,total:0}}},mounted:function(){},methods:{onPageSize:function(e){this.pageState.pageSize=e},onPageNumber:function(e){this.pageState.pageNumber=e}}},l,!1,function(e){a("gDaO")},"data-v-249de94f",null);t.default=r.exports},gDaO:function(e,t){}});
//# sourceMappingURL=7.4a62c7da19514dcb5bbb.js.map