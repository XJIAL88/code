webpackJsonp([0],{kjuJ:function(t,e){},qp82:function(t,e,r){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=r("gyMJ"),n=r("0xDb"),o={data:function(){return{form:{name:"",type:1,startAt:"",endAt:"",numberTotal:"",numberDaily:"",new:!1,rule:"",newDaylimit:30,resource:[]},rules:{name:[{required:!0,message:"请输入活动名称",trigger:"blur"}],startAt:[{required:!0,message:"请输入活动时间",trigger:"blur"}],groupId:[{required:!0,message:"请输入活动范围",trigger:"blur"}]},props:{label:"title",value:"id",children:"son"},typeList:[{value:1,label:"开门抽奖"}],groupList:[],categoryList:[],isFirst:!0,list:[]}},mounted:function(){this.initInfo(),this.getCategoryList()},methods:{renderFirst:function(){var t=this;this.$createElement;this.$bus.$emit("changeBreadCrumbAction",function(e,r){return r("div",{},[r("el-button",{attrs:{size:"medium",type:"primary"},on:{click:t.next}},["下一步"]),r("el-button",{attrs:{size:"medium"},on:{click:t.goBack}},["取消"])])})},renderBreadCrumbAction:function(){var t=this;this.$createElement;this.$bus.$emit("changeBreadCrumbAction",function(e,r){return r("div",{},[r("el-button",{attrs:{size:"medium",type:"primary"},on:{click:t.submitForm}},["提交审批"]),r("el-button",{attrs:{size:"medium"},on:{click:t.goBack}},["取消"])])})},initInfo:function(){var t=Object(n.l)("activityInfo");t?(this.form=t,this.isFirst=!1,this.$nextTick(this.renderBreadCrumbAction)):this.$nextTick(this.renderFirst)},getGroupList:function(){var t=this;a.a.post("activityGroupGetList").then(function(e){t.groupList=e.data.dataList})},getCategoryList:function(){var t=this;a.a.post("resourceCategoryList").then(function(e){t.categoryList=e.data.dataList})},updateDay:function(){var t=this;this.$prompt("请输入天数","提示",{confirmButtonText:"确定",cancelButtonText:"取消",inputPattern:/^[1-9][0-9]{0,14}$/,inputErrorMessage:"请输入整数"}).then(function(e){var r=e.value;t.form.newDaylimit=r}).catch(function(){t.$message({type:"info",message:"取消输入"})})},next:function(){var t=this;this.$refs.form.validate(function(e){if(!e)return!1;Object(n.n)("activityInfo",t.form),t.isFirst=!1,t.$nextTick(t.renderBreadCrumbAction)})},submitForm:function(){var t=this,e=[];if(0!=this.list.length){var r=!1;this.list.forEach(function(a){var n=t.check(a);if(n)return t.showTips(n),void(r=!0);var o={resourceId:a.resourceId,resourceName:a.resourceName,categoryId:a.categoryId[a.categoryId.length-1],category:a.category,grantType:1,number:a.number};e.push(o)}),r||(this.form.resource=e,a.a.post("activityInsert",this.form).then(function(e){t.goBack()}))}else this.showTips("请添加资源")},goBack:function(){Object(n.m)("activityInfo"),this.$router.back()},changeGroup:function(t){var e=this;this.groupList.forEach(function(r){r.id==t&&(e.form.gorupTitle=r.title)})},addItem:function(){this.list.push({categoryId:[],category:"",number:"",resourceList:[],resourceId:"",resourceName:"",grantType:1})},delItem:function(t){this.list.splice(t,1)},changeCategory:function(t){var e=t.categoryId,r=e.length;if(r)for(var a=0;a<e.length;a++)this.categoryList.forEach(function(a){a.id==e[0]&&(1==r?t.category=a.title:a.son.forEach(function(a){a.id==e[1]&&(2==r?t.category=a.title:a.son.forEach(function(r){r.id==e[2]&&(t.category=r.title)}))}))});else t.category="";t.resourceId="",t.resourceName="",t.canApply=null,t.number="",this.getResurceList(t)},changeResource:function(t){t.resourceId&&t.resourceList.forEach(function(e){e.id==t.resourceId&&(t.resourceName=e.name,t.canApply=e.quantity-e.used-e.freeze)})},getResurceList:function(t){var e={category:"",categoryId:t.categoryId[t.categoryId.length-1],platform:"",name:"",status:1,grantType:"",callType:"",pageSize:1e3,pageNumber:1};a.a.post("resourceGetList",e,{loading:!1}).then(function(e){t.resourceList=e.data.dataList})},check:function(t){return t.category?t.resourceId?t.number?parseInt(t.number)>parseInt(t.canApply)?"申请数量不能大于可申请数量":"":"请输入申请数量":"请选择资源":"请选择资源类型"},showTips:function(t){this.$alert(t,"提示",{type:"warning",confirmButtonText:"确定",callback:function(t){}})}}},i={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"activity-edit"},[t.isFirst?r("el-form",{ref:"form",attrs:{model:t.form,"label-width":"100px",rules:t.rules}},[r("el-row",{staticClass:"form-width"},[r("el-col",{attrs:{span:12}},[r("el-form-item",{attrs:{label:"活动名称",prop:"name"}},[r("el-input",{model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),t._v(" "),r("el-form-item",{attrs:{label:"活动时间",prop:"startAt"}},[r("FormDatetimeRange",{attrs:{"start-at":t.form.startAt,"end-at":t.form.endAt},on:{"update:startAt":function(e){t.$set(t.form,"startAt",e)},"update:endAt":function(e){t.$set(t.form,"endAt",e)}}})],1),t._v(" "),r("el-form-item",{staticClass:"draw-count",attrs:{label:"抽奖次数",prop:"status"}},[r("el-input",{attrs:{placeholder:"无限"},model:{value:t.form.numberTotal,callback:function(e){t.$set(t.form,"numberTotal",e)},expression:"form.numberTotal"}},[r("template",{slot:"prepend"},[t._v("每用户共可参加")]),t._v(" "),r("template",{slot:"append"},[t._v("次")])],2),t._v(" "),r("el-input",{staticStyle:{"margin-top":"15px"},attrs:{placeholder:"无限"},model:{value:t.form.numberDaily,callback:function(e){t.$set(t.form,"numberDaily",e)},expression:"form.numberDaily"}},[r("template",{slot:"prepend"},[t._v("每用户每天可参加")]),t._v(" "),r("template",{slot:"append"},[t._v("次")])],2)],1)],1),t._v(" "),r("el-col",{attrs:{span:12}},[r("el-form-item",{attrs:{label:"活动类型",prop:"status"}},[r("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},t._l(t.typeList,function(t){return r("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),r("el-form-item",{attrs:{label:"新人专享",prop:"new"}},[r("el-switch",{model:{value:t.form.new,callback:function(e){t.$set(t.form,"new",e)},expression:"form.new"}}),t._v("（新人：注册\n                    "),r("el-button",{attrs:{type:"text"},on:{click:t.updateDay}},[t._v(t._s(t.form.newDaylimit))]),t._v("天内的用户）\n                ")],1)],1)],1),t._v(" "),r("el-row",[r("el-col",{attrs:{span:16}},[r("el-form-item",{attrs:{label:"活动规则"}},[r("el-input",{attrs:{type:"textarea",rows:4},model:{value:t.form.rule,callback:function(e){t.$set(t.form,"rule",e)},expression:"form.rule"}})],1)],1)],1)],1):t._e(),t._v(" "),t.isFirst?t._e():r("div",[r("div",{staticStyle:{padding:"10px 30px","font-size":"16px"}},[t._v("申请资源：")]),t._v(" "),r("el-table",{staticStyle:{width:"901px","margin-left":"30px"},attrs:{data:t.list,border:""}},[r("el-table-column",{attrs:{prop:"categoryId",label:"资源类型",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[r("el-cascader",{attrs:{clearable:"",options:t.categoryList,props:t.props,"show-all-levels":!1},on:{change:function(r){t.changeCategory(e.row)}},model:{value:e.row.categoryId,callback:function(r){t.$set(e.row,"categoryId",r)},expression:"scope.row.categoryId"}})]}}])}),t._v(" "),r("el-table-column",{attrs:{prop:"name",label:"资源名称",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[r("el-select",{attrs:{clearable:"",placeholder:"请选择"},on:{change:function(r){t.changeResource(e.row)}},model:{value:e.row.resourceId,callback:function(r){t.$set(e.row,"resourceId",r)},expression:"scope.row.resourceId"}},t._l(e.row.resourceList,function(t){return r("el-option",{key:t.id,attrs:{label:t.name,value:t.id}})}))]}}])}),t._v(" "),r("el-table-column",{attrs:{prop:"canApply",label:"可申请总数量",width:"180",align:"center"}}),t._v(" "),r("el-table-column",{attrs:{prop:"number",label:"申请数量",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[r("el-input",{attrs:{placeholder:"请输入",type:"number"},model:{value:e.row.number,callback:function(r){t.$set(e.row,"number",r)},expression:"scope.row.number"}})]}}])}),t._v(" "),r("el-table-column",{attrs:{label:"操作",width:"180",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){return[r("el-button",{attrs:{type:"text"},on:{click:function(r){t.delItem(e.$index)}}},[t._v("删除")])]}}])})],1),t._v(" "),r("el-button",{staticStyle:{"margin-left":"30px","margin-top":"15px"},attrs:{type:"primary",icon:"el-icon-plus"},on:{click:t.addItem}},[t._v("添加资源")])],1)],1)},staticRenderFns:[]};var s=r("VU/8")(o,i,!1,function(t){r("kjuJ")},"data-v-7d8c243d",null);e.default=s.exports}});
//# sourceMappingURL=0.2b66f48e2fce7e974f00.js.map