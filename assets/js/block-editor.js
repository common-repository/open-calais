!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},n.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=17)}([function(e,t){!function(){e.exports=this.lodash}()},function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},function(e,t){!function(){e.exports=this.wp.apiFetch}()},function(e,t){function n(t){"@babel/helpers - typeof";return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=n=function(e){return typeof e}:e.exports=n=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(t)}e.exports=n},function(e,t){!function(){e.exports=this.wp.url}()},function(e,t){!function(){e.exports=this.wp.data}()},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},function(e,t){!function(){e.exports=this.wp.hooks}()},function(e,t){!function(){e.exports=this.wp.compose}()},function(e,t){!function(){e.exports=this.wp.components}()},function(e,t,n){var r=n(18);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&r(e,t)}},function(e,t,n){var r=n(5),o=n(3);e.exports=function(e,t){return!t||"object"!==r(t)&&"function"!=typeof t?o(e):t}},function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}e.exports=function(e,t,r){return t&&n(e.prototype,t),r&&n(e,r),e}},function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},function(e,t,n){"use strict";n.r(t);var r=n(1),o=n(5),i=n.n(o),s=n(16),a=n.n(s),c=n(15),u=n.n(c),p=n(3),l=n.n(p),f=n(14),b=n.n(f),d=n(9),m=n.n(d),h=n(13),O=n.n(h),g=n(8),y=n.n(g),v=n(0),j=n(2),T=n(12),_=n(7),x=n(11),C=n(4),w=n.n(C),S=n(6);function P(e){return function(){var t,n=m()(e);if(function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],function(){})),!0}catch(e){return!1}}()){var r=m()(this).constructor;t=Reflect.construct(n,arguments,r)}else t=n.apply(this,arguments);return b()(this,t)}}function k(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),n.push.apply(n,r)}return n}function E(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?k(Object(n),!0).forEach(function(t){y()(e,t,n[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):k(Object(n)).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))})}return e}var R={per_page:-1,orderby:"count",order:"desc",_fields:"id,name"},A=function(e,t){return e.toLowerCase()===t.toLowerCase()},q=function(e){return E({},e,{name:Object(v.unescape)(e.name)})},D=function(e){return Object(v.map)(e,q)},U=function(e){O()(n,e);var t=P(n);function n(){var e;return a()(this,n),(e=t.apply(this,arguments)).onChange=e.onChange.bind(l()(e)),e.searchTerms=e.searchTerms.bind(l()(e)),e.findOrCreateTerm=e.findOrCreateTerm.bind(l()(e)),e.state={loading:!Object(v.isEmpty)(e.props.terms),availableTerms:[],selectedTerms:[],OpenCalais:"object"===("undefined"==typeof OpenCalais?"undefined":i()(OpenCalais))&&!Object(v.isEmpty)(OpenCalais)},e.renderOpenCalaisSuggestions=e.renderOpenCalaisSuggestions.bind(l()(e)),e.renderOpenCalaisSuggestion=e.renderOpenCalaisSuggestion.bind(l()(e)),e}return u()(n,[{key:"componentDidMount",value:function(){var e=this;if(Object(v.isEmpty)(this.props.terms)||(this.initRequest=this.fetchTerms({include:this.props.terms.join(","),per_page:-1}),this.initRequest.then(function(){e.setState({loading:!1})},function(t){"abort"!==t.statusText&&e.setState({loading:!1})})),this.state.OpenCalais){var t=Object(v.map)(OpenCalais,function(e){return e.term_id});this.fetchTerms({include:t.join(","),per_page:-1})}}},{key:"componentWillUnmount",value:function(){Object(v.invoke)(this.initRequest,["abort"]),Object(v.invoke)(this.searchRequest,["abort"])}},{key:"componentDidUpdate",value:function(e){e.terms!==this.props.terms&&this.updateSelectedTerms(this.props.terms)}},{key:"fetchTerms",value:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},n=this.props.taxonomy,r=E({},R,{},t),o=w()({path:Object(S.addQueryArgs)("/wp/v2/".concat(n.rest_base),r)});return o.then(D).then(function(t){e.setState(function(e){return{availableTerms:e.availableTerms.concat(t.filter(function(t){return!Object(v.find)(e.availableTerms,function(e){return e.id===t.id})}))}}),e.updateSelectedTerms(e.props.terms)}),o}},{key:"updateSelectedTerms",value:function(){var e=this,t=(arguments.length>0&&void 0!==arguments[0]?arguments[0]:[]).reduce(function(t,n){var r=Object(v.find)(e.state.availableTerms,function(e){return e.id===n});return r&&t.push(r.name),t},[]);this.setState({selectedTerms:t})}},{key:"findOrCreateTerm",value:function(e){var t=this,n=this.props.taxonomy,r=Object(v.escape)(e);return w()({path:"/wp/v2/".concat(n.rest_base),method:"POST",data:{name:r}}).catch(function(o){return"term_exists"===o.code?(t.addRequest=w()({path:Object(S.addQueryArgs)("/wp/v2/".concat(n.rest_base),E({},R,{search:r}))}).then(D),t.addRequest.then(function(t){return Object(v.find)(t,function(t){return A(t.name,e)})})):Promise.reject(o)}).then(q)}},{key:"onChange",value:function(e){var t=this,n=Object(v.uniqBy)(e,function(e){return e.toLowerCase()});this.setState({selectedTerms:n});var r=n.filter(function(e){return!Object(v.find)(t.state.availableTerms,function(t){return A(t.name,e)})}),o=function(e,t){return e.map(function(e){return Object(v.find)(t,function(t){return A(t.name,e)}).id})};if(0===r.length)return this.props.onUpdateTerms(o(n,this.state.availableTerms),this.props.taxonomy.rest_base);Promise.all(r.map(this.findOrCreateTerm)).then(function(e){var r=t.state.availableTerms.concat(e);return t.setState({availableTerms:r}),t.props.onUpdateTerms(o(n,r),t.props.taxonomy.rest_base)})}},{key:"searchTerms",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";Object(v.invoke)(this.searchRequest,["abort"]),e.length<=1||(this.searchRequest=this.fetchTerms({search:e}))}},{key:"renderOpenCalaisSuggestions",value:function(){var e=this.state.OpenCalais?OpenCalais:[];return Object(v.map)(e,this.renderOpenCalaisSuggestion)}},{key:"renderOpenCalaisSuggestion",value:function(e){var t=-1!==this.state.selectedTerms.indexOf(e.name)?"is-selected":"";return void 0===e.term_id&&(t+=" open-calais-existing-false"),Object(r.createElement)("li",null,Object(r.createElement)("a",{className:t,onClick:this.handleOpenCalaisClick(e)},e.name))}},{key:"handleOpenCalaisClick",value:function(e){var t=this;return function(){var n=t.state.selectedTerms;n.push(e.name),t.onChange(n)}}},{key:"render",value:function(){var e=this.props,t=e.slug,n=e.taxonomy;if(!e.hasAssignAction)return null;var o=this.state,i=o.loading,s=o.availableTerms,a=o.selectedTerms,c=s.map(function(e){return e.name}),u=Object(v.get)(n,["labels","add_new_item"],"post_tag"===t?Object(j.__)("Add New Tag"):Object(j.__)("Add New Term")),p=Object(v.get)(n,["labels","singular_name"],"post_tag"===t?Object(j.__)("Tag"):Object(j.__)("Term")),l=Object(j.sprintf)(Object(j._x)("%s added","term"),p),f=Object(j.sprintf)(Object(j._x)("%s removed","term"),p),b=Object(j.sprintf)(Object(j._x)("Remove %s","term"),p);return[Object(r.createElement)(T.FormTokenField,{value:a,suggestions:c,onChange:this.onChange,onInputChange:Object(v.debounce)(this.searchTerms,1e3),maxSuggestions:20,disabled:i,label:u,messages:{added:l,removed:f,remove:b}}),!i&&this.state.OpenCalais&&Object(r.createElement)("div",{className:"open-calais"},Object(r.createElement)("h3",null,"Suggested tags from Open Calais"),Object(r.createElement)("ul",null,this.renderOpenCalaisSuggestions()))]}}]),n}(r.Component),F=Object(x.compose)(Object(_.withSelect)(function(e,t){var n=t.slug,r=e("core/editor").getCurrentPost,o=(0,e("core").getTaxonomy)(n);return{hasCreateAction:!!o&&Object(v.get)(r(),["_links","wp:action-create-"+o.rest_base],!1),hasAssignAction:!!o&&Object(v.get)(r(),["_links","wp:action-assign-"+o.rest_base],!1),terms:o?e("core/editor").getEditedPostAttribute(o.rest_base):[],taxonomy:o}}),Object(_.withDispatch)(function(e){return{onUpdateTerms:function(t,n){e("core/editor").editPost(y()({},n,t))}}}))(U),N=n(10);Object(N.addFilter)("editor.PostTaxonomyType","open-calais",function(e){return function(t){return"post_tag"===t.slug?Object(r.createElement)(F,t):Object(r.createElement)(e,t)}})},function(e,t){function n(t,r){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,r)}e.exports=n}]);