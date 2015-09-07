
jQuery.effects||function(e,t){var n=e.uiBackCompat!==!1,r="ui-effects-";e.effects={effect:{}},function(t,n){function p(e,t,n){var r=a[t.type]||{};return e==null?n||!t.def?null:t.def:(e=r.floor?~~e:parseFloat(e),isNaN(e)?t.def:r.mod?(e+r.mod)%r.mod:0>e?0:r.max<e?r.max:e)}function d(e){var n=o(),r=n._rgba=[];return e=e.toLowerCase(),h(s,function(t,i){var s,o=i.re.exec(e),a=o&&i.parse(o),f=i.space||"rgba";if(a)return s=n[f](a),n[u[f].cache]=s[u[f].cache],r=n._rgba=s._rgba,!1}),r.length?(r.join()==="0,0,0,0"&&t.extend(r,c.transparent),n):c[e]}function v(e,t,n){return n=(n+1)%1,n*6<1?e+(t-e)*n*6:n*2<1?t:n*3<2?e+(t-e)*(2/3-n)*6:e}var r="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor".split(" "),i=/^([\-+])=\s*(\d+\.?\d*)/,s=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1]*2.55,e[2]*2.55,e[3]*2.55,e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],o=t.Color=function(e,n,r,i){return new t.Color.fn.parse(e,n,r,i)},u={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},a={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},f=o.support={},l=t("<p>")[0],c,h=t.each;l.style.cssText="background-color:rgba(255,255,255,1)",f.rgba=l.style.backgroundColor.indexOf("rgba")>-1,h(u,function(e,t){t.cache="_"+e,t.props.alpha={idx:3,type:"percent",def:1}}),o.fn=t.extend(o.prototype,{parse:function(r,i,s,a){if(r===n)return this._rgba=[null,null,null,null],this;if(r.jquery||r.nodeType)r=t(r).css(i),i=n;var f=this,l=t.type(r),v=this._rgba=[];i!==n&&(r=[r,i,s,a],l="array");if(l==="string")return this.parse(d(r)||c._default);if(l==="array")return h(u.rgba.props,function(e,t){v[t.idx]=p(r[t.idx],t)}),this;if(l==="object")return r instanceof o?h(u,function(e,t){r[t.cache]&&(f[t.cache]=r[t.cache].slice())}):h(u,function(t,n){var i=n.cache;h(n.props,function(e,t){if(!f[i]&&n.to){if(e==="alpha"||r[e]==null)return;f[i]=n.to(f._rgba)}f[i][t.idx]=p(r[e],t,!0)}),f[i]&&e.inArray(null,f[i].slice(0,3))<0&&(f[i][3]=1,n.from&&(f._rgba=n.from(f[i])))}),this},is:function(e){var t=o(e),n=!0,r=this;return h(u,function(e,i){var s,o=t[i.cache];return o&&(s=r[i.cache]||i.to&&i.to(r._rgba)||[],h(i.props,function(e,t){if(o[t.idx]!=null)return n=o[t.idx]===s[t.idx],n})),n}),n},_space:function(){var e=[],t=this;return h(u,function(n,r){t[r.cache]&&e.push(n)}),e.pop()},transition:function(e,t){var n=o(e),r=n._space(),i=u[r],s=this.alpha()===0?o("transparent"):this,f=s[i.cache]||i.to(s._rgba),l=f.slice();return n=n[i.cache],h(i.props,function(e,r){var i=r.idx,s=f[i],o=n[i],u=a[r.type]||{};if(o===null)return;s===null?l[i]=o:(u.mod&&(o-s>u.mod/2?s+=u.mod:s-o>u.mod/2&&(s-=u.mod)),l[i]=p((o-s)*t+s,r))}),this[r](l)},blend:function(e){if(this._rgba[3]===1)return this;var n=this._rgba.slice(),r=n.pop(),i=o(e)._rgba;return o(t.map(n,function(e,t){return(1-r)*i[t]+r*e}))},toRgbaString:function(){var e="rgba(",n=t.map(this._rgba,function(e,t){return e==null?t>2?1:0:e});return n[3]===1&&(n.pop(),e="rgb("),e+n.join()+")"},toHslaString:function(){var e="hsla(",n=t.map(this.hsla(),function(e,t){return e==null&&(e=t>2?1:0),t&&t<3&&(e=Math.round(e*100)+"%"),e});return n[3]===1&&(n.pop(),e="hsl("),e+n.join()+")"},toHexString:function(e){var n=this._rgba.slice(),r=n.pop();return e&&n.push(~~(r*255)),"#"+t.map(n,function(e){return e=(e||0).toString(16),e.length===1?"0"+e:e}).join("")},toString:function(){return this._rgba[3]===0?"transparent":this.toRgbaString()}}),o.fn.parse.prototype=o.fn,u.hsla.to=function(e){if(e[0]==null||e[1]==null||e[2]==null)return[null,null,null,e[3]];var t=e[0]/255,n=e[1]/255,r=e[2]/255,i=e[3],s=Math.max(t,n,r),o=Math.min(t,n,r),u=s-o,a=s+o,f=a*.5,l,c;return o===s?l=0:t===s?l=60*(n-r)/u+360:n===s?l=60*(r-t)/u+120:l=60*(t-n)/u+240,f===0||f===1?c=f:f<=.5?c=u/a:c=u/(2-a),[Math.round(l)%360,c,f,i==null?1:i]},u.hsla.from=function(e){if(e[0]==null||e[1]==null||e[2]==null)return[null,null,null,e[3]];var t=e[0]/360,n=e[1],r=e[2],i=e[3],s=r<=.5?r*(1+n):r+n-r*n,o=2*r-s;return[Math.round(v(o,s,t+1/3)*255),Math.round(v(o,s,t)*255),Math.round(v(o,s,t-1/3)*255),i]},h(u,function(e,r){var s=r.props,u=r.cache,a=r.to,f=r.from;o.fn[e]=function(e){a&&!this[u]&&(this[u]=a(this._rgba));if(e===n)return this[u].slice();var r,i=t.type(e),l=i==="array"||i==="object"?e:arguments,c=this[u].slice();return h(s,function(e,t){var n=l[i==="object"?e:t.idx];n==null&&(n=c[t.idx]),c[t.idx]=p(n,t)}),f?(r=o(f(c)),r[u]=c,r):o(c)},h(s,function(n,r){if(o.fn[n])return;o.fn[n]=function(s){var o=t.type(s),u=n==="alpha"?this._hsla?"hsla":"rgba":e,a=this[u](),f=a[r.idx],l;return o==="undefined"?f:(o==="function"&&(s=s.call(this,f),o=t.type(s)),s==null&&r.empty?this:(o==="string"&&(l=i.exec(s),l&&(s=f+parseFloat(l[2])*(l[1]==="+"?1:-1))),a[r.idx]=s,this[u](a)))}})}),h(r,function(e,n){t.cssHooks[n]={set:function(e,r){var i,s,u="";if(t.type(r)!=="string"||(i=d(r))){r=o(i||r);if(!f.rgba&&r._rgba[3]!==1){s=n==="backgroundColor"?e.parentNode:e;while((u===""||u==="transparent")&&s&&s.style)try{u=t.css(s,"backgroundColor"),s=s.parentNode}catch(a){}r=r.blend(u&&u!=="transparent"?u:"_default")}r=r.toRgbaString()}try{e.style[n]=r}catch(l){}}},t.fx.step[n]=function(e){e.colorInit||(e.start=o(e.elem,n),e.end=o(e.end),e.colorInit=!0),t.cssHooks[n].set(e.elem,e.start.transition(e.end,e.pos))}}),t.cssHooks.borderColor={expand:function(e){var t={};return h(["Top","Right","Bottom","Left"],function(n,r){t["border"+r+"Color"]=e}),t}},c=t.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(jQuery),function(){function i(){var t=this.ownerDocument.defaultView?this.ownerDocument.defaultView.getComputedStyle(this,null):this.currentStyle,n={},r,i;if(t&&t.length&&t[0]&&t[t[0]]){i=t.length;while(i--)r=t[i],typeof t[r]=="string"&&(n[e.camelCase(r)]=t[r])}else for(r in t)typeof t[r]=="string"&&(n[r]=t[r]);return n}function s(t,n){var i={},s,o;for(s in n)o=n[s],t[s]!==o&&!r[s]&&(e.fx.step[s]||!isNaN(parseFloat(o)))&&(i[s]=o);return i}var n=["add","remove","toggle"],r={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};e.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(t,n){e.fx.step[n]=function(e){if(e.end!=="none"&&!e.setAttr||e.pos===1&&!e.setAttr)jQuery.style(e.elem,n,e.end),e.setAttr=!0}}),e.effects.animateClass=function(t,r,o,u){var a=e.speed(r,o,u);return this.queue(function(){var r=e(this),o=r.attr("class")||"",u,f=a.children?r.find("*").andSelf():r;f=f.map(function(){var t=e(this);return{el:t,start:i.call(this)}}),u=function(){e.each(n,function(e,n){t[n]&&r[n+"Class"](t[n])})},u(),f=f.map(function(){return this.end=i.call(this.el[0]),this.diff=s(this.start,this.end),this}),r.attr("class",o),f=f.map(function(){var t=this,n=e.Deferred(),r=jQuery.extend({},a,{queue:!1,complete:function(){n.resolve(t)}});return this.el.animate(this.diff,r),n.promise()}),e.when.apply(e,f.get()).done(function(){u(),e.each(arguments,function(){var t=this.el;e.each(this.diff,function(e){t.css(e,"")})}),a.complete.call(r[0])})})},e.fn.extend({_addClass:e.fn.addClass,addClass:function(t,n,r,i){return n?e.effects.animateClass.call(this,{add:t},n,r,i):this._addClass(t)},_removeClass:e.fn.removeClass,removeClass:function(t,n,r,i){return n?e.effects.animateClass.call(this,{remove:t},n,r,i):this._removeClass(t)},_toggleClass:e.fn.toggleClass,toggleClass:function(n,r,i,s,o){return typeof r=="boolean"||r===t?i?e.effects.animateClass.call(this,r?{add:n}:{remove:n},i,s,o):this._toggleClass(n,r):e.effects.animateClass.call(this,{toggle:n},r,i,s)},switchClass:function(t,n,r,i,s){return e.effects.animateClass.call(this,{add:n,remove:t},r,i,s)}})}(),function(){function i(t,n,r,i){e.isPlainObject(t)&&(n=t,t=t.effect),t={effect:t},n==null&&(n={}),e.isFunction(n)&&(i=n,r=null,n={});if(typeof n=="number"||e.fx.speeds[n])i=r,r=n,n={};return e.isFunction(r)&&(i=r,r=null),n&&e.extend(t,n),r=r||n.duration,t.duration=e.fx.off?0:typeof r=="number"?r:r in e.fx.speeds?e.fx.speeds[r]:e.fx.speeds._default,t.complete=i||n.complete,t}function s(t){return!t||typeof t=="number"||e.fx.speeds[t]?!0:typeof t=="string"&&!e.effects.effect[t]?n&&e.effects[t]?!1:!0:!1}e.extend(e.effects,{version:"1.9.1",save:function(e,t){for(var n=0;n<t.length;n++)t[n]!==null&&e.data(r+t[n],e[0].style[t[n]])},restore:function(e,n){var i,s;for(s=0;s<n.length;s++)n[s]!==null&&(i=e.data(r+n[s]),i===t&&(i=""),e.css(n[s],i))},setMode:function(e,t){return t==="toggle"&&(t=e.is(":hidden")?"show":"hide"),t},getBaseline:function(e,t){var n,r;switch(e[0]){case"top":n=0;break;case"middle":n=.5;break;case"bottom":n=1;break;default:n=e[0]/t.height}switch(e[1]){case"left":r=0;break;case"center":r=.5;break;case"right":r=1;break;default:r=e[1]/t.width}return{x:r,y:n}},createWrapper:function(t){if(t.parent().is(".ui-effects-wrapper"))return t.parent();var n={width:t.outerWidth(!0),height:t.outerHeight(!0),"float":t.css("float")},r=e("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),i={width:t.width(),height:t.height()},s=document.activeElement;try{s.id}catch(o){s=document.body}return t.wrap(r),(t[0]===s||e.contains(t[0],s))&&e(s).focus(),r=t.parent(),t.css("position")==="static"?(r.css({position:"relative"}),t.css({position:"relative"})):(e.extend(n,{position:t.css("position"),zIndex:t.css("z-index")}),e.each(["top","left","bottom","right"],function(e,r){n[r]=t.css(r),isNaN(parseInt(n[r],10))&&(n[r]="auto")}),t.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),t.css(i),r.css(n).show()},removeWrapper:function(t){var n=document.activeElement;return t.parent().is(".ui-effects-wrapper")&&(t.parent().replaceWith(t),(t[0]===n||e.contains(t[0],n))&&e(n).focus()),t},setTransition:function(t,n,r,i){return i=i||{},e.each(n,function(e,n){var s=t.cssUnit(n);s[0]>0&&(i[n]=s[0]*r+s[1])}),i}}),e.fn.extend({effect:function(){function a(n){function u(){e.isFunction(i)&&i.call(r[0]),e.isFunction(n)&&n()}var r=e(this),i=t.complete,s=t.mode;(r.is(":hidden")?s==="hide":s==="show")?u():o.call(r[0],t,u)}var t=i.apply(this,arguments),r=t.mode,s=t.queue,o=e.effects.effect[t.effect],u=!o&&n&&e.effects[t.effect];return e.fx.off||!o&&!u?r?this[r](t.duration,t.complete):this.each(function(){t.complete&&t.complete.call(this)}):o?s===!1?this.each(a):this.queue(s||"fx",a):u.call(this,{options:t,duration:t.duration,callback:t.complete,mode:t.mode})},_show:e.fn.show,show:function(e){if(s(e))return this._show.apply(this,arguments);var t=i.apply(this,arguments);return t.mode="show",this.effect.call(this,t)},_hide:e.fn.hide,hide:function(e){if(s(e))return this._hide.apply(this,arguments);var t=i.apply(this,arguments);return t.mode="hide",this.effect.call(this,t)},__toggle:e.fn.toggle,toggle:function(t){if(s(t)||typeof t=="boolean"||e.isFunction(t))return this.__toggle.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="toggle",this.effect.call(this,n)},cssUnit:function(t){var n=this.css(t),r=[];return e.each(["em","px","%","pt"],function(e,t){n.indexOf(t)>0&&(r=[parseFloat(n),t])}),r}})}(),function(){var t={};e.each(["Quad","Cubic","Quart","Quint","Expo"],function(e,n){t[n]=function(t){return Math.pow(t,e+2)}}),e.extend(t,{Sine:function(e){return 1-Math.cos(e*Math.PI/2)},Circ:function(e){return 1-Math.sqrt(1-e*e)},Elastic:function(e){return e===0||e===1?e:-Math.pow(2,8*(e-1))*Math.sin(((e-1)*80-7.5)*Math.PI/15)},Back:function(e){return e*e*(3*e-2)},Bounce:function(e){var t,n=4;while(e<((t=Math.pow(2,--n))-1)/11);return 1/Math.pow(4,3-n)-7.5625*Math.pow((t*3-2)/22-e,2)}}),e.each(t,function(t,n){e.easing["easeIn"+t]=n,e.easing["easeOut"+t]=function(e){return 1-n(1-e)},e.easing["easeInOut"+t]=function(e){return e<.5?n(e*2)/2:1-n(e*-2+2)/2}})}()}(jQuery);(function(e,t){e.effects.effect.highlight=function(t,n){var r=e(this),i=["backgroundImage","backgroundColor","opacity"],s=e.effects.setMode(r,t.mode||"show"),o={backgroundColor:r.css("backgroundColor")};s==="hide"&&(o.opacity=0),e.effects.save(r,i),r.show().css({backgroundImage:"none",backgroundColor:t.color||"#ffff99"}).animate(o,{queue:!1,duration:t.duration,easing:t.easing,complete:function(){s==="hide"&&r.hide(),e.effects.restore(r,i),n()}})}})(jQuery);;(function ($) {

    var displayProperties = function (object, deep, prefix, maxDeep) {
        var text = "";
        var counter = 0;

        if (!prefix) prefix = "";
        if (!deep) deep = 0;

        for (var property in object) {
            counter++;
            text += prefix + "[" + counter + "] " + property + " = " + object[property] + "\n";
            if (maxDeep != 0 && deep <= maxDeep && typeof (object[property]) == "object") text += displayProperties(object[property], deep + 1, prefix + "  ", maxDeep);
            if (counter >= 80) break;
        }
        return text;
    };

    $.debugShowObject = function (object, maxDeep) {
        alert(displayProperties(object, 0, "", maxDeep));
    };

    $.debugObjectToString = function (object, maxDeep) {
        return displayProperties(object, maxDeep);
    };

})(jQuery);;/**
 * Preset resources for Social Locker
 * for jQuery: http://joextension-media.com/plugin/social-locker-for-jquery/get
 * for Wordpress: http://joextension-media.com/plugin/social-locker-for-wordpress/get
 *
 * Copyright 2013, joextension, http://joextension-media.com/portfolio
 * Help Desk: http://support.joextension-media.com/
 */

(function ($) {

    /**
    * Text resources.
    */

    if (!$.joextension) $.joextension = {};
    if (!$.joextension.sociallocker) $.joextension.sociallocker = {};
    if (!$.joextension.sociallocker.lang) $.joextension.sociallocker.lang = {};

    $.joextension.sociallocker.lang = {

        defaultHeader:      "This content is locked!",
        defaultMessage:     "Please support us, use one of the buttons below to unlock the content.",
        orWait:             'or wait',
        seconds:            's',   
        close:              'Close',
        
        // default button labels
        facebook_like:      'me gusta',
        facebook_share:     'compartir',
        twitter_tweet:      'tweet',  
        twitter_follow:     'seguinos en twitter',  
        google_plus:        '+1 us',  
        google_share:       'compartir',
        linkedin_share:     'compartir'
    };
    
    /**
    * Available buttons
    */
   
    if (!$.joextension.sociallocker.buttons) $.joextension.sociallocker.buttons = {};
    $.joextension.sociallocker.buttons = [
        "facebook-like",
        "facebook-share",
        "google-plus",
        "google-share",
        "twitter-tweet",
        "twitter-follow",
        "linkedin-share",    
        "#"
    ]

    /**
    * Presets options for styles.
    * You can add some options that will be applied when a specified theme is used.
    */

    if (!$.joextension.sociallocker.presets) $.joextension.sociallocker.presets = {};
    
    /* starter theme */

    $.joextension.sociallocker.presets['starter'] = {
        
        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: false
        }
    };
    
    /* secrets theme */
    
    $.joextension.sociallocker.presets['secrets'] = {

        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: true
        },
        
        triggers: {
            overlayRender: function(options, networkName, buttonName, isTouch){
                var overlay = isTouch ? $("<a></a>") : $("<div></div>");
                var title = options.title || $.joextension.sociallocker.lang.socialLock[networkName + "_" + buttonName];
                
                overlay.addClass("jo-sociallocker-button-overlay") 
                      .append(
                       $("<div class='jo-sociallocker-overlay-front'></div>")
                            .append($("<div class='jo-sociallocker-overlay-icon'></div>"))
                            .append($("<div class='jo-sociallocker-overlay-text'>" + title + "</div>"))
                       )
                      .append($("<div class='jo-sociallocker-overlay-header'></div>"))
                      .append($("<div class='jo-sociallocker-overlay-back'></div>"));
                
                return overlay;
            }
        }
    };
    
    /* dandyish theme */
    
    $.joextension.sociallocker.presets['dandyish'] = {

        buttons: {
            layout: 'vertical',
            counter: true,
            unsupported: ['twitter-follow']
        },
        effects: {
            flip: false
        }
    };
    
    /* glass theme */
    
    $.joextension.sociallocker.presets['glass'] = {
        _iPhoneBug: false,
        
        buttons: {
            layout: 'horizontal',
            counter: true
        },
        effects: {
            flip: false
        }
    };

})(jQuery);;/**
* Helper Tools:
* - cookies getter/setter
* - md5 hasher
* - lightweight widget factory
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';

    if (!$.joextension) $.joextension = {};
    if (!$.joextension.tools) $.joextension.tools = {};

    /*
    * Cookie's function.
    * Allows to set or get cookie.
    *
    * Based on the plugin jQuery Cookie Plugin
    * https://github.com/carhartl/jquery-cookie
    *
    * Copyright 2011, Klaus Hartl
    * Dual licensed under the MIT or GPL Version 2 licenses.
    * http://www.opensource.org/licenses/mit-license.php
    * http://www.opensource.org/licenses/GPL-2.0
    */
    $.joextension.tools.cookie = $.joextension.tools.cookie || function (key, value, options) {

        // Sets cookie
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) {
            options = $.extend({}, options);

            if (value === null || value === undefined) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '',
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // Gets cookie.
        options = value || {};
        var decode = options.raw ? function (s) { return s; } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) {
            if (decode(pair[0]) === key) return decode(pair[1] || '');
        }
        return null;
    };

    /*
    * jQuery MD5 Plugin 1.2.1
    * https://github.com/blueimp/jQuery-MD5
    *
    * Copyright 2010, Sebastian Tschan
    * https://blueimp.net
    *
    * Licensed under the MIT license:
    * http://creativecommons.org/licenses/MIT/
    * 
    * Based on
    * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
    * Digest Algorithm, as defined in RFC 1321.
    * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
    * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
    * Distributed under the BSD License
    * See http://pajhome.org.uk/crypt/md5 for more info.
    */
    $.joextension.tools.hash = $.joextension.tools.hash || function (str) {

        var hash = 0;
        if (str.length == 0) return hash;
        for (var i = 0; i < str.length; i++) {
            var charCode = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + charCode;
            hash = hash & hash;
        }
        hash = hash.toString(16);
        hash = hash.replace("-", "0");

        return hash;
    };

    /**
    * Checks does a browers support 3D transitions:
    * https://gist.github.com/3794226
    */
    $.joextension.tools.has3d = $.joextension.tools.has3d || function () {

        var el = document.createElement('p'),
            has3d,
            transforms = {
                'WebkitTransform': '-webkit-transform',
                'OTransform': '-o-transform',
                'MSTransform': '-ms-transform',
                'MozTransform': '-moz-transform',
                'Transform': 'transform'
            };

        // Add it to the body to get the computed style
        document.body.insertBefore(el, null);

        for (var t in transforms) {
            if (el.style[t] !== undefined) {
                el.style[t] = 'translate3d(1px,1px,1px)';
                has3d = window.getComputedStyle(el).getPropertyValue(transforms[t]);
            }
        }

        document.body.removeChild(el);
        return (has3d !== undefined && has3d.length > 0 && has3d !== "none");
    };

    /**
    * Returns true if a current user use a touch device
    * http://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript
    */
    $.joextension.isTouch = $.joextension.isTouch || function () {

        return !!('ontouchstart' in window) // works on most browsers 
            || !!('onmsgesturechange' in window); // works on ie10
    };

    /**
    * joextension Widget Factory.
    * Supports:
    * - creating a jquery widget via the standart jquery way
    * - call of public methods.
    */
    $.joextension.widget = function (pluginName, pluginObject) {

        var factory = {

            createWidget: function (element, options) {
                var widget = $.extend(true, {}, pluginObject);

                widget.element = $(element);
                widget.options = $.extend(true, widget.options, options);

                if (widget._init) widget._init();
                if (widget._create) widget._create();

                $.data(element, 'plugin_' + pluginName, widget);
            },

            callMethod: function (widget, methodName) {
                widget[methodName] && widget[methodName]();
            }
        };

        $.fn[pluginName] = function () {
            var args = arguments;
            var argsCount = arguments.length;

            this.each(function () {

                var widget = $.data(this, 'plugin_' + pluginName);

                // a widget is not created yet
                if (!widget && argsCount <= 1) {
                    factory.createWidget(this, argsCount ? args[0] : false);

                    // a widget is created, the public method with no args is being called
                } else if (argsCount == 1) {
                    factory.callMethod(widget, args[0]);
                }
            });
        };
    };

})(jQuery);;/**
* Part of jQuery Migrate plugin.
* It allows to add the support of the browser property into jquery 1.9.0+

* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

// Limit scope pollution from any deprecated API
(function() {
    if ( jQuery.browser ) return;
    
    var matched, browser;

// Use of jQuery.browser is frowned upon.
// More details: http://api.jquery.com/jQuery.browser
// jQuery.uaMatch maintained for back-compat
    jQuery.uaMatch = function( ua ) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
            /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
            /(msie) ([\w.]+)/.exec( ua ) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
            [];

        return {
            browser: match[ 1 ] || "",
            version: match[ 2 ] || "0"
        };
    };

    matched = jQuery.uaMatch( navigator.userAgent );
    browser = {};

    if ( matched.browser ) {
        browser[ matched.browser ] = true;
        browser.version = matched.version;
    }

// Chrome is Webkit, but Webkit is also Safari.
    if ( browser.chrome ) {
        browser.webkit = true;
    } else if ( browser.webkit ) {
        browser.safari = true;
    }

    jQuery.browser = browser;

    jQuery.sub = function() {
        function jQuerySub( selector, context ) {
            return new jQuerySub.fn.init( selector, context );
        }
        jQuery.extend( true, jQuerySub, this );
        jQuerySub.superclass = this;
        jQuerySub.fn = jQuerySub.prototype = this();
        jQuerySub.fn.constructor = jQuerySub;
        jQuerySub.sub = this.sub;
        jQuerySub.fn.init = function init( selector, context ) {
            if ( context && context instanceof jQuery && !(context instanceof jQuerySub) ) {
                context = jQuerySub( context );
            }

            return jQuery.fn.init.call( this, selector, context, rootjQuerySub );
        };
        jQuerySub.fn.init.prototype = jQuerySub.fn;
        var rootjQuerySub = jQuerySub(document);
        return jQuerySub;
    };

})();;/**
* Facebook Like Button widget for jQuery
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker_facebook_like) return;

    $.joextension.widget("sociallocker_facebook_like", {
        options: {},

        _defaults: {
            
            // URL to like/share
            url: null,
            
            // App Id used to get extended contol tools (optionly).
            // You can create your own app here: https://developers.facebook.com/apps	
            appId: 0,               
            // Language of the button labels. By default en_US.
            lang: 'es_ES',
            // Button layout, available: standart, button_count, box_count. By default 'standart'.
            layout: 'standart',
            // Button container width in px, by default 450.
            width: 'auto',
            // The verb to display in the button. Only 'like' and 'recommend' are supported. By default 'like'.
            verbToDisplay: "like",
            // The color scheme of the plugin. By default 'light'.
            colorScheme: "light",
            // The font of the button. By default 'tahoma'.
            font: 'tahoma',
            // A label for tracking referrals.
            ref: null,
            // set to 'none' to hide the count box
            count: 'standart',
            
            // unlock event
            unlock: null
        },

        _create: function () {
            var self = this;

            this._prepareOptions();
            this._setupEvents();

            this.element.data('joextension-facebookButton', this);
            this._createButton();

            $.joextension.connector.connect("facebook", this.options, function (sdk) {
                sdk.render(self.element);
            });
        },

        _prepareOptions: function () {

            var values = $.extend({}, this._defaults);
            this.options = $.extend(values, this.options);
            this.url = (!this.options.url) ? window.location.href : this.options.url;
        },

        _setupEvents: function () {
            var self = this;

            $(document).bind('fb-like', function (e, url) {
                if (self.options.unlock && self.url == url) {
                    self.options.unlock(url, self);
                }
            });
        },

        /**
        * Generates an html code for the button using specified options.
        */
        _createButton: function () {

            this.button = $("<div class='fake-fb-like'></div>");
               
            this.wrap = $("<div class='jo-social-button jo-facebook-button'></div>")
                        .appendTo(this.element)
                        .append(this.button);
                        
            if (this.options.count == 'none') {
                this.wrap.addClass('jo-facebook-like-count-none');
                this.wrap.addClass('jo-facebook-like-' + this.options.lang);
            }

            this.button.data('facebook-widget', this);
            this.button.attr("data-show-faces", false);
            this.button.attr("data-send", false); 
            
            if (this.options.url) this.button.attr("data-href", this.options.url);
            if (this.options.font) this.button.attr("data-font", this.options.font);
            if (this.options.colorScheme) this.button.attr("data-colorscheme", this.options.colorScheme);
            if (this.options.ref) this.button.attr("data-ref", this.options.ref);
            if (this.options.width) this.button.attr("data-width", this.options.width);
            if (this.options.layout) this.button.attr("data-layout", this.options.layout);
            if (this.options.verbToDisplay) this.button.attr("data-action", this.options.verbToDisplay);
        },

        getHtmlToRender: function () {
            return this.wrap;
        }
    });
    
})(jQuery);;/**
* Facebook Share Button widget for jQuery
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker_facebook_button) return;

    $.joextension.widget("sociallocker_facebook_share", {
        options: {},

        _defaults: {
            
            // URL to like/share
            url: jQuery('#owloo-social-unlock').attr('data-url'),

            // App Id used to get extended contol tools (optionly).
            // You can create your own app here: https://developers.facebook.com/apps	
            appId: 554618394649460,               
            // standard or vertical
            layout: 'standard',
            // set to 'none' to hide the count box
            count: 'standart',
            
            // data to share
            name: null,
            caption: null,
            description: null,
            image: null,
            
            // unlock event
            unlock: function(){alert('compartió 1')}
        },

        _create: function () {
            var self = this;

            this._prepareOptions();

            this.element.data('joextension-facebookButton', this);
            this._createButton();

            $.joextension.connector.connect("facebook", this.options, function (sdk) {
                sdk.render(self.element);
            });
        },

        _prepareOptions: function () {

            var values = $.extend({}, this._defaults);
            this.options = $.extend(values, this.options);
            this.url = (!this.options.url) ? window.location.href : this.options.url;
        },

        /**
        * Generates an html code for the button using specified options.
        */
        _createButton: function () {
            var count = 0;
            var self = this;
            
            this.button = $("<div class='jo-facebook-share jo-facebook-layout-" + this.options.layout + "'></div>");
            this.button.append($('<div class="fb-share-button jo-facebook-share-icon" data-href="'+self.url+'" data-type="button_count"></div>'));
            this.button.data('facebook-widget', this);
            
            this.wrap = $("<div class='jo-social-button jo-facebook-button'></div>")
                        .appendTo(this.element)
                        .append(this.button);
                        
            if (this.options.count == 'none') {
                this.button.addClass('jo-facebook-share-count-none');
            }
                        
           this.button.click(function(){
            FB.ui(
               {
                 method: 'feed',
                 name: self.name,
                 link: self.url,
                 picture: self.image,
                 caption: self.caption,
                 description: self.description
               },
               function(response) {
               	 alert('compartió 3');
                 if (response && response.post_id) {
                 	alert('compartió 2');
                    self.button.find('.jo-facebook-share-count').text(self.getCountLabel(count++));
                    self.options.unlock && self.options.unlock(self.url, self);
                 }
               }
            );
                
            return false;
           });
           
            $.joextension.connector.connect("facebook", this.options, function (sdk) {
                window.FB.api("/", { "id": self.url }, function (response) {
                    if (response.error) return;
                    count = self.getCountLabel( response.shares || count );
                    self.button.find('.jo-facebook-share-count').text(count);
                });
            });
        },
        
        getCountLabel: function(count) {
            if ( count >= 1000 ) return Math.floor( count / 1000 ) + "K";
            return count;
        },

        getHtmlToRender: function () {
            return this.wrap;
        }
    });
  
})(jQuery);;/**
* Google Plus One widget for jQuery
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker_google_button) return;
    
    $.joextension.widget("sociallocker_google_button", {
        options: {},

        _defaults: {
            
            // URL to plus one
            url: null,
            // plus or share
            type: null,
            
            // Language of the button labels. By default en-US.
            // https://developers.google.com/+/plugins/+1button/#available-languages
            lang: 'en-US',
            // small, medium, standard, tall (https://developers.google.com/+/plugins/+1button/#button-sizes)
            size: null,
            // Sets the annotation to display next to the button.
            annotation: null,
            // Button container width in px, by default 450.
            width: null,
            // Sets the horizontal alignment of the button assets within its frame.
            align: "left",
            // Sets the preferred positions to display hover and confirmation bubbles, which are relative to the button.
            // comma-separated list of top, right, bottom, left
            expandTo: "",
            // To disable showing recommendations within the +1 hover bubble, set recommendations to false.    
            recommendations: true,
            // Events
            unlock: null
        },

        _create: function () {
            var self = this;

            this._prepareOptions();
            this._setupEvents();

            this.element.data('joextension-googleButton', this);
            this._createButton();

            $.joextension.connector.connect("google", this.options, function (sdk) {
                sdk.render(self.element);
            });
        },

        _prepareOptions: function () {

            var values = $.extend({}, this._defaults);
            this.options = $.extend(values, this.options);
            this.url = (!this.options.url) ? window.location : this.options.url;
        },

        _setupEvents: function () {
            var self = this;

            $(document).bind('gl-plus', function (e, url) {
                $(".gc-bubbleDefault").hide();
                if (self.options.unlock && (self.url == url || (self.url + '/') == url)) {
                    self.options.unlock(url, self);
                }
            });
            
            $(document).bind('gl-share', function (e, url) {
                $(".gc-bubbleDefault").hide();
                if (self.options.unlock && (self.url == url || (self.url + '/') == url)) {
                    self.options.unlock(url, self);
                }
            });      
        },

        /**
        * Generates an html code for the button using specified options.
        */
        _createButton: function () {

            this.button = ( this.options.type == 'plus' )
                                ? $("<div class='fake-g-plusone'></div>")
                                : $("<div class='fake-g-share'></div>");
                                
            this.wrap = $("<div class='jo-social-button jo-google-button'></div>")
                        .appendTo(this.element)
                        .append(this.button);
            
            this.button.data('google-widget', this);

            if (this.options.url) this.button.attr("data-href", this.options.url);
            if (this.options.size) this.button.attr("data-size", this.options.size);
            if (this.options.annotation) this.button.attr("data-annotation", this.options.annotation);
            if (this.options.align) this.button.attr("data-align", this.options.align);
            if (this.options.expandTo) this.button.attr("data-expandTo", this.options.expandTo);
            if (this.options.recommendations) this.button.attr("data-recommendations", this.options.recommendations);
        },

        getHtmlToRender: function () {
            return this.wrap;
        }
    });
    
    $.fn.sociallocker_google_plus = function( options ){
        options.type = 'plus';
        return $(this).sociallocker_google_button(options);
    };
    
    $.fn.sociallocker_google_share = function( options ){
        options.type = 'share';
        return $(this).sociallocker_google_button(options);
    };
    
})(jQuery);;/**
* Twitter Button widget for jQuery
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker_twitter_button) return;
    
    $.joextension.widget("sociallocker_twitter_button", {

        options: {},

        _defaults: {

            // URL of the page to share.
            url: null,
            // tweet or follow button
            type: null,

            // Default Tweet text
            // [tweet]
            text: null,
            // Screen name of the user to attribute the Tweet to
            // [tweet]
            via: null,
            // The user's screen name shows up by default, but you can opt not to 
            // show the screen name in the button. 
            // [follow]
            showScreenName: false,
            // Related accounts
            // [tweet]
            related: null,
            // Count box position (none, horizontal, vertical)
            // [tweet]
            count: 'horizontal',
            // Followers count display
            // [follow]
            showCount: true,
            // The language for the Tweet Button
            // [tweet][follow]
            lang: 'en',
            // URL to which your shared URL resolves
            // [tweet]
            counturl: null,
            // The size of the rendered button (medium, large)
            size: 'large',
            
            // unlock event
            unlock: null
        },

        _create: function () {
            var self = this;

            this._prepareOptions();
            this._setupEvents();

            this.element.data('joextension-twitterButton', this);
            this._createButton();

            $.joextension.connector.connect("twitter", this.options, function (sdk) {
                sdk.render(self.element);
            });
        },

        _prepareOptions: function () {

            var values = $.extend({}, this._defaults);

            for (var prop in this._defaults) {
                if (this.element.data(prop) !== undefined) values[prop] = this.element.data(prop);
            }

            this.options = $.extend(values, this.options);

            if (!this.options.url && $("link[rel='canonical']").length > 0)
                this.options.url = $("link[rel='canonical']").attr('href');
            
            this.url = this.options.url || window.location.href;

        },

        _setupEvents: function () {
            var self = this;

            $(document).bind('tw-tweet', function (e, target, data) {
                if (self.options.type != 'tweet') return;
                var url = $(target).parent().attr('data-url-to-compare');
                if (self.url == url && self.options.unlock) self.options.unlock && self.options.unlock(url, self);
            });
            
            $(document).bind('tw-follow', function (e, target, data) {
                if (self.options.type != 'follow') return;
                var url = $(target).parent().attr('data-url-to-compare');
                if (self.url == url && self.options.unlock) self.options.unlock && self.options.unlock(url, self);
            });   
        },

        /**
        * Generates an html code for the button using specified options.
        */
        _createButton: function () {

            // What will title be used?
            var title;
            if (this.options.type == 'follow') {
                title = 'Follow Me';
                if (!this.options.url) title = "[Error] Setup an URL of your Twitter account.";
            } else {
                title = 'Tweet';
            }

            this.button = $("<a href='https://twitter.com/share'>" + title + "</a>");
            this.button.data('twitter-widget', this);
            
            this.wrap = $("<div class='jo-social-button jo-twitter-button'></div>")
                        .appendTo(this.element)
                        .append(this.button);
            
            if (this.options.type == 'tweet') {
                this.wrap.addClass('jo-twitter-tweet');
                this.button.addClass('twitter-share-button');
            }
            if (this.options.type == 'follow') {
                this.wrap.addClass('jo-twitter-follow');
                this.button.addClass('twitter-follow-button');
            }

            if (this.options.type == 'follow') this.button.attr('href', this.url);
            else this.button.attr("data-url", this.url);

            this.button.attr("data-show-count", this.options.showCount);
            if (this.options.via) this.button.attr("data-via", this.options.via);
            if (this.options.text) this.button.attr("data-text", this.options.text);
            if (this.options.related) this.button.attr("data-related", this.options.related);
            if (this.options.count) this.button.attr("data-count", this.options.count);
            if (this.options.showCount) this.button.attr("data-show-count", this.options.showCount); 
            if (this.options.lang) this.button.attr("data-lang", this.options.lang);
            if (this.options.counturl) this.button.attr("data-counturl", this.options.counturl);
            if (this.options.hashtags) this.button.attr("data-hashtags", this.options.hashtags);
            if (this.options.alignment) this.button.attr("data-alignment", this.options.alignment);
            if (this.options.size) this.button.attr("data-size", this.options.size);
            if (this.options.dnt) this.button.attr("data-dnt", this.options.dnt);
            if (this.options.showScreenName) this.button.attr("data-show-screen-name", this.options.showScreenName);
            
            this.wrap.attr('data-url-to-compare', this.url);
        },

        getHtmlToRender: function () {
            return this.button;
        }
    });
    
    $.fn.sociallocker_twitter_tweet = function( options ){
        options.type = 'tweet';
        return $(this).sociallocker_twitter_button(options);
    };
    
    $.fn.sociallocker_twitter_follow = function( options ){

        options.type = 'follow';
        return $(this).sociallocker_twitter_button(options);
    };

})(jQuery);;/**
* LinkedIn Share Button widget for jQuery
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker_linkedin_share) return;
    
    $.joextension.widget("sociallocker_linkedin_share", {

        options: {},

        _defaults: {

            // URL of the page to share.
            url: null,

            // Count box position (none, horizontal, vertical)
            // [tweet]
            counter: 'right',
            
            // unlock event
            unlock: null
        },

        _create: function () {
            var self = this;

            this._prepareOptions();
            this._setupEvents();
            this._createButton();
            this.element.data('linkedin-widget', this);
            
            $.joextension.connector.connect("linkedin", this.options, function (sdk) {
                sdk.render(self.element);
            });
        },

        _prepareOptions: function () {

            var values = $.extend({}, this._defaults);

            for (var prop in this._defaults) {
                if (this.element.data(prop) !== undefined) values[prop] = this.element.data(prop);
            }

            this.options = $.extend(values, this.options);
            this.url = this.options.url || window.location.href;

        },

        _setupEvents: function () {
            var self = this;

            $(document).bind('ln-share', function (e, url) {
                if (self.url == url && self.options.unlock) self.options.unlock && self.options.unlock(url, self);
            });
        },

        /**
        * Generates an html code for the button using specified options.
        */
        _createButton: function () {

            // What will title be used?
            
            this.button = $('<script type="IN/Share"></script>');
            this.wrap = $("<div class='jo-social-button jo-linkedin-button'></div>")
                        .appendTo(this.element)
                        .append(this.button);

            if (this.options.counter) this.button.attr("data-counter", this.options.counter);
            if (this.options.url) this.button.attr("data-url", this.url);
        },

        getHtmlToRender: function () {
            return this.button;
        }
    });

})(jQuery);;/**
* joextension Local State Provider
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';

    if (!$.joextension) $.joextension = {};
    if (!$.joextension.providers) $.joextension.providers = {};

    /**
    * Returns a state provide for the Strict Mode.
    */
    $.joextension.providers.clientStoreStateProvider = function (networkName, buttonName, url, options) {

        this.networkName = networkName;
        this.buttonName = buttonName;
        
        this.demo = options.demo;
        this.useCookies = options.locker.useCookies;
        this.cookiesLifetime = options.locker.cookiesLifetime;
        
        this.url = url;
        this.identity = "page_" + $.joextension.tools.hash(this.url) + "_hash_" + networkName + "-" + buttonName;

        /**
        * Does the provider contain an unlocked state?
        */
        this.isUnlocked = function () {
            if (this.demo) return false;
            return (this._getValue()) ? true : false;
        };

        /**
        * Does the provider contain a locked state?
        */
        this.isLocked = function () {
            return !this.isUnlocked();
        };

        /**
        * Gets a state and calls the callback with the one.
        */
        this.getState = function (callback) {
            if (this.demo) return callback(false);
            callback(this.isUnlocked());
        };

        /**
        * Sets state of a locker to provider.
        */
        this.setState = function (value) {
            if (this.demo) return true;
            return value == "unlocked" ? this._setValue() : this._removeValue();
        };

        this._setValue = function () {
            var self = this;

            return localStorage && !this.useCookies
                ? localStorage.setItem(this.identity, true)
                : $.joextension.tools.cookie(this.identity, true, { expires: self.cookiesLifetime, path: "/" });
        };

        this._getValue = function () {

            if (localStorage && !this.useCookies) {

                var value = localStorage.getItem(this.identity);
                if (value) return value;

                value = $.joextension.tools.cookie(this.identity);
                if (value) this._setValue();

                return value;
            }

            return $.joextension.tools.cookie(this.identity);

        };

        this._removeValue = function () {
            if (localStorage) localStorage.removeItem(this.identity);
            $.joextension.tools.cookie(this.identity, null);
        };
    };

})(jQuery);;/**
* SDK Connector for Social Networks:
* - Facebook
* - Twitter
* - Google
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';

    if (!$.joextension) $.joextension = {};

    $.joextension.connector = $.joextension.connector || {

        sdk: [

        // --
        // Facebook 
        // --
        {
            name: 'facebook',
            url: '//connect.facebook.net/{lang}/all.js',
            scriptId: 'facebook-jssdk',
            hasParams: true,
            isRender: true,

            isLoaded: function () {
                return (typeof (window.FB) === "object");
            },

            pre: function () {

                // root for facebook sdk
                $("#fb-root").length == 0 && $("<div id='fb-root'></div>").appendTo($("body"));

                // sets sdk language
                var lang = (this.options && this.options.lang) || "en_US";
                this.url = this.url.replace("{lang}", lang);
            },

            createEvents: function (isLoaded) {
                var self = this;

                var load = function () {

                    window.FB.init({
                        appId: (self.options && self.options.appId) || null,
                        status: true,
                        cookie: true,
                        xfbml: true
                    });

                    window.FB.Event.subscribe('edge.create', function (response) {
                        $(document).trigger('fb-like', [response]);
                    });

                    // The initialization is executed only one time.
                    // Any others attempts will call an empty function.
                    window.FB.init = function () { };
                    $(document).trigger(self.name + '-init');
                };

                if (isLoaded) { load(); return; }

                if (window.fbAsyncInit) var predefined = window.fbAsyncInit;
                window.fbAsyncInit = function () {
                    load(); predefined && predefined();
                    window.fbAsyncInit = function () { };
                };
            },

            render: function (widget) {

                var api = widget.data('joextension-facebookButton');
                if (!api) return;

                var $html = api.getHtmlToRender();
                $html.find('.fake-fb-like').addClass('fb-like');       
                window.FB.XFBML.parse($html[0]);
            }
        },

        // --
        // Twitter 
        // --
        {
        name: 'twitter',
        url: '//platform.twitter.com/widgets.js',
        scriptId: 'twitter-wjs',
        hasParams: false,
        isRender: true,

        pre: function () {

            var canonical = ($("link[rel='canonical']").length > 0)
				    ? $("link[rel='canonical']").attr('href')
				    : null;

            $(".twitter-share-button").each(function (index, item) {
                var $item = $(item);
                var $target = $(item).parent();

                if ($target.attr('data-url-to-compare')) return;

                var url = $item.attr("data-url");
                if (!url && canonical) url = canonical;
                url = (!url) ? window.location : url;

                $item.parent().attr('data-url-to-compare', url);
            });
        },

        isLoaded: function () {
            return (typeof (window.__twttrlr) !== "undefined");
        },

        createEvents: function (isLoaded) {
            var self = this;

            var load = function () {

                window.twttr.events.bind('tweet', function (event) {
                    $(document).trigger('tw-tweet', [event.target, event.data]);
                });

                window.twttr.events.bind('follow', function (event) {
                    $(document).trigger('tw-follow', [event.target, event.data]);
                });

                $(document).trigger(self.name + '-init');
            };

            if (isLoaded) { load(); return; }

            if (!window.twttr) window.twttr = {};
            if (!window.twttr.ready) window.twttr = $.extend(window.twttr, { _e: [], ready: function (f) { this._e.push(f); } });
            
            twttr.ready(function (twttr) { load(); });
        },

        /**
        * A twitter buttons works by other way.
        * When the script loaded 
        */
        render: function (widget) {

            var api = widget.data('joextension-twitterButton');
            if (!api) return;

            var $html = api.getHtmlToRender().parent();
            var attemptCounter = 5;

            // Chrome fix
            // If there is SDK script on the same page that is loading now when a tweet button will not appear.
            // Setup special timeout function what will check 5 times when we can render the twitter button.
            var timoutFunction = function () {
                if ($html.find('iframe').length > 0) return;

                if (window.twttr.widgets && window.twttr.widgets.load) {
                    window.twttr.widgets.load($html[0]);
                    widget.trigger('tw-render');
                } else {
                    if (attemptCounter <= 0) return;
                    attemptCounter--;

                    setTimeout(function () {
                        timoutFunction();
                    }, 500);
                }
            };

            timoutFunction();
        }
    },

    // --
    // Google 
    // --
    {
        name: 'google',
        url: '//apis.google.com/js/plusone.js',
        scriptId: 'google-jssdk',
        hasParams: true,
        isRender: true,

        pre: function () {

            // sets sdk language
            var lang = (this.options && this.options.lang) || "en";
            window.___gcfg = window.___gcfg || { lang: lang };

            window.joextensionPlusOneCallback = function (data) {
                if (data.state == "on") $(document).trigger('gl-plus', [data.href]);
            };
            
            window.joextensionGoogleShareCallback = function (data) {
                $(document).trigger('gl-share', [data.id]);
            };    
        },

        isLoaded: function () {
            return (typeof (window.gapi) === "object");
        },

        render: function (widget) {

            var api = widget.data('joextension-googleButton');
            if (!api) return;

            var self = this;

            setTimeout(function () {
                var $html = api.getHtmlToRender();
                
                var plusone = $html.find('.fake-g-plusone');
                if ( plusone.length > 0 ) {
                    self._addCallbackToControl($html);
                    plusone.addClass('g-plusone');
                    window.gapi.plusone.go($html[0]);
                    return;
                }
                
                var share = $html.find('.fake-g-share');
                if ( share.length > 0 ) {
                    share.attr("data-onendinteraction", "joextensionGoogleShareCallback");
                    share.addClass('g-plus').attr('data-action', 'share');

                    gapi.plus.render(share);
                    return;
                }
 
            }, 100);
        },

        _addCallbackToControl: function ($control) {

            var $elm = (!$control.is(".g-plusone")) ? $control.find(".fake-g-plusone") : $control;

            var callback = $elm.attr("data-callback");
            if (callback && callback != "joextensionPlusOneCallback") {
                var newCallback = "__plusone_" + callback;
                window[newCallback] = function (data) {
                    window[callback](data);
                    window.joextensionPlusOneCallback(data);
                };
                $elm.attr("data-callback", newCallback);
            } else {
                $elm.attr("data-callback", "joextensionPlusOneCallback");
            }
        }
    },
    
    // --
    // Linked In 
    // --
    {
        name: 'linkedin',
        url: '//platform.linkedin.com/in.js',
        scriptId: 'linkedin-jssdk',
        hasParams: false,
        isRender: true,

        pre: function () {

            window.joextensionLinkedInShareCallback = function (data) {
                $(document).trigger('ln-share', [data]);
            };
        },

        isLoaded: function () {
            return (typeof (window.IN) === "object");
        },

        render: function (widget) {

            var api = widget.data('linkedin-widget');
            if (!api) return;

            setTimeout(function () {
                var $html = api.getHtmlToRender();
                $html.attr("data-onsuccess", "joextensionLinkedInShareCallback");
                IN.init();
            }, 100);
        }
    }
    ],

    // contains dictionary sdk_name => is_sdk_ready (bool)
    _ready: {},

    // contains dictionaty sdk_name => is_sdk_connected (bool)
    _connected: {},

    /**
    * Get SDK object by its name.
    */
    getSDK: function (name) {

        for (var index in this.sdk) if (this.sdk[index].name == name) return this.sdk[index];
        return null;
    },

    /**
    * Checks whether a specified SDK is connected (sdk script is included into a page).
    */
    isConnected: function (sdk) {
        return ($("#" + sdk.scriptId).length > 0 || $("script[src='*" + sdk.url + "']").length > 0);
    },

    /**
    * Gets loading SDK script on a page.
    */
    getLoadingScript: function (sdk) {
        var byId = $("#" + sdk.scriptId);
        var byScr = $("script[src='*" + sdk.url + "']");
        return (byId.length > 0) ? byId : byScr;
    },

    /**
    * Checks whether a specified SQK is loaded and ready to use.
    */
    isLoaded: function (sdk) {
        return this.isConnected(sdk) && sdk.isLoaded && sdk.isLoaded();
    },

    /**
    * Connects SKD if it's needed then calls callback.
    */
    connect: function (name, options, callback) {
        var self = this, sdk = this.getSDK(name);

        if (!sdk) {
            console && console.log('Invalide SDK name: ' + name);
            return;
        }

        sdk.options = options;

        // fire or bind callback
        if (callback) this._ready[name]
                ? callback(sdk)
                : $(document).bind(name + "-init", function () { callback(sdk); });

        if (this._connected[name]) return;

        // sets the default method if it's not specified
        if (!sdk.createEvents) {
            sdk.createEvents = function (isLoaded) {
                var selfSDK = this;

                var load = function () {
                    $(document).trigger(selfSDK.name + '-init');
                };

                if (isLoaded) { load(); return; }

                $(document).bind(selfSDK.name + "-script-loaded", function () {
                    load();
                });
            };
        }

        if (sdk.pre) sdk.pre();

        var loaded = this.isLoaded(sdk);
        var connected = this.isConnected(sdk);

        $(document).bind(name + "-init", function () { self._ready[name] = true; });

        // subscribes to events
        sdk.createEvents(loaded);

        // conencts sdk
        if (!connected) {

            var scriptConnection = function () {

                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.id = sdk.scriptId;
                script.src = sdk.url;

                var bodyElement = document.getElementsByTagName('body')[0];
                bodyElement.appendChild(script);
            };

            sdk.isRender
                ? scriptConnection()
                : $(function () { $(function () { scriptConnection(); }); });
        }

        // subsribes to onload event
        if (!loaded) {

            var loadingScript = this.getLoadingScript(sdk)[0];

            if (loadingScript) {
                loadingScript.onreadystatechange = loadingScript.onload = function () {
                    var state = loadingScript.readyState;
                    if ((!state || /loaded|complete/.test(state))) $(document).trigger(sdk.name + '-script-loaded');
                };
            }
        }

        this._connected[name] = true;
    }
};

})(jQuery);;/**
* Social Locker
* for jQuery: http://joextension-media.com/plugin/sociallocker-for-jquery/get
* for Wordpress: http://joextension-media.com/plugin/sociallocker-for-wordpress/get
*
* Copyright 2013, joextension, http://joextension-media.com/portfolio
* Help Desk: http://support.joextension-media.com/
*/

(function ($) {
    'use strict';
    if ($.fn.sociallocker) return;

    $.joextension.widget("sociallocker", {

        options: {},

        // The variable stores a current locker state.
        _isLocked: false,

        // Defauls option's values.
        _defaults: {
            _iPhoneBug: false,
            
            // Url that used to like/tweet/plusone.
            // It's obligatory to check whether a user likes a page.
            url: null,

            // Text above the locker buttons.
            text: {
                header: $.joextension.sociallocker.lang.defaultHeader,
                message: $.joextension.sociallocker.lang.defaultMessage
            },

            // Theme applied to the locker
            theme: "starter",

            // Sets whether the locker keep the state of always appears
            demo: false,

            // Social buttons to use
            buttons: {

                // horizontal or vertical
                layout: 'horizontal',

                // an order of the buttons, available buttons:
                // -
                // twitter: twitter-tweet, twitter-follow
                // facebook: facebook-like, facebook-share
                // google: google-plus, google-share
                // -
                order: ["facebook-like", "facebook-share", "twitter-tweet", "google-plus"],

                // fixed or auto
                size: "auto",

                // hide or show counters for the buttons
                counter: true
            },

            // --
            // Locker functionality.
            locker: {

                // Sets whether a user may remove the locker by a cross placed at the top-right corner.
                close: false,
                // Sets a timer interval to unlock content when the zero is reached.
                // If the value is 0, the timer will not be created. 
                timer: 0,
                // Sets whether the locker appears for mobiles devides.
                mobile: true,

                // force to use cookies instead of a local storage
                useCookies: true,
                // the number of days for cookies lifetime
                cookiesLifetime: 0
            },

            // -
            // Content that will be showen after unlocking.
            // -
            content: null,

            // --
            // Events set
            events: {

                lock: null,
                unlock: null,
                ready: null,

                unlockByCross: null,
                unlockByTimer: null
            },

            // --
            // Locker effects
            effects: {

                // Turns on the Flip effect.
                flip: false,

                // Turns on the Highlight effect.
                highlight: true
            },

            // --
            // Facebook Options
            facebook: {
                url: null,

                // App Id used to get extended contol tools (optionly).
                // You can create your own app here: https://developers.facebook.com/apps
                appId: null,
                // Language of the button labels. By default en_US.
                lang: 'en_US',
                // The color scheme of the plugin. By default 'light'.
                colorScheme: "light",
                // The font of the button. By default 'tahoma'.
                font: 'tahoma',
                // A label for tracking referrals.
                ref: null,
                
                // - Separeted options for each buttons.
     
                like: {
                    title: $.joextension.sociallocker.lang.facebook_like
                },
                share: {
                    title: $.joextension.sociallocker.lang.facebook_share
                }
            },

            twitter: {
                url: null,

                // Screen name of the user to attribute the Tweet to
                via: null,
                // Default Tweet text
                text: null,
                // Related accounts
                related: null,
                // The language for the Tweet Button
                lang: 'es',
                // URL to which your shared URL resolves
                counturl: null,
                
                // - Separeted options for each buttons.
     
                tweet: {
                    title: $.joextension.sociallocker.lang.twitter_tweet
                },
                follow: {
                    title: $.joextension.sociallocker.lang.twitter_follow
                }
            },

            google: {
                url: null,

                // Language of the button labels. By default en-US.
                // https://developers.google.com/+/plugins/+1button/#available-languages
                lang: 'en-US',
                // Sets the annotation to display next to the button.
                annotation: null,
                // To disable showing recommendations within the +1 hover bubble, set recommendations to false.    
                recommendations: true,
                
                // - Separeted options for each buttons.
     
                plus: {
                    title: $.joextension.sociallocker.lang.google_plus
                },
                share: {
                    title: $.joextension.sociallocker.lang.google_share
                }
            },
            
            linkedin: {
                url: null,
                counter: "right",
                
                // - Separeted options for each buttons.
                
                share: {
                    title: $.joextension.sociallocker.lang.linkedin_share 
                }
            }
        },

        /**
        * Enter point to start creating the locker. 
        */
        _create: function () {
            var self = this;

            // parse options
            this._processOptions();

            // don't show a locker in ie7
            if ($.browser.msie && parseInt($.browser.version, 10) === 7) {
                this._unlock("ie7"); return;
            }

            // check mobile devices
            if (!this.options.locker.mobile && this._isMobile()) {
                this._unlock("mobile"); return;
            }
            
            // remove buttons that are not supported by iPhone
            if ((/iPhone/i).test(navigator.userAgent) && this.options._iPhoneBug ) {
                var twitterIndex = $.inArray("twitter-tweet", this.options.buttons.order);
                if (twitterIndex >= 0) this.options.buttons.order.splice(twitterIndex, 1);
            }
            
            // remove a google share button for Opera, IE8 and mobile devices
            if ( $.browser.opera || $.browser.msie || this._isTabletOrMobile() ) {
                var googleIndex = $.inArray("google-share", this.options.buttons.order);   
                if (googleIndex >= 0) this.options.buttons.order.splice(googleIndex, 1);
            }
            
            // unlock the locker if no buttons are defined
            if (this.options.buttons.order.length == 0) {
                this._unlock("nobuttons"); return;
            }

            // creates provider
            this._controller = this._createProviderController();

            // get state to decide what our next step is
            this._controller.getState(function (state) {
                state ? self._unlock("provider") : self._lock();
                self.options.events.ready && self.options.events.ready(state);
            });
        },

        /**
        * Creates and returns a controler of providers by using the options.
        */
        _createProviderController: function () {
            var self = this;
            this._providers = {};

            var totalCount = 0;

            for (var providerIndex in this.options.buttons.order) {
                var sourceName = this.options.buttons.order[providerIndex];
                if (typeof (sourceName) != 'string') continue;
                
                if ( !this._isValidButton(sourceName) ) {
                    this._setError("The button '" + sourceName + "' not found.");
                    return;
                }
                
                // button separator
                if ( sourceName == '#' ) continue;

                var parts = sourceName.split('-');
                var networkName = parts[0];
                var buttonName = parts[1];
                
                var buttonOptions = $.extend({}, this.options[networkName]);
                if ( this.options[networkName][buttonName] ) buttonOptions = $.extend(buttonOptions, this.options[networkName][buttonName] );
                var url = buttonOptions.url || this.options.url || window.location.href;
                
                if ( !this._providers[sourceName] ) {
                    this._providers[sourceName] = 
                        new $.joextension.providers.clientStoreStateProvider( networkName, buttonName, url, self.options );
                    totalCount++;
                }
            }

            // controller of providers
            return {

                /**
                * Gets result state for all defined providers.
                */
                getState: function (callback) {
                    
                    var counter = totalCount;
                    var resultState = false;

                    for (var name in self._providers) {
                        var provider = self._providers[name];

                        provider.getState(function (state) {
                            counter--; resultState = resultState || state;
                            if (counter == 0) callback(resultState, provider);
                        });
                    }
                }
            };
        },

        /**
        * Processes the locker options.
        */
        _processOptions: function () {
            var theme = this.options.theme || this._defaults.theme;
            var options = $.extend(true, {}, this._defaults);

            // uses preset options
            if ($.joextension.sociallocker.presets[theme]) {
                options = $.extend(true, {}, options, $.joextension.sociallocker.presets[theme]);

                if ( 
                    $.joextension.sociallocker.presets[theme].buttons && 
                    $.joextension.sociallocker.presets[theme].buttons.order) {
                    
                    options.buttons.order = $.joextension.sociallocker.presets[theme].buttons.order;
                }
            }

            // users user defined options
            options = $.extend(true, options, this.options);

            if (this.options.buttons && this.options.buttons.order) {
                options.buttons.order = this.options.buttons.order;
            }

            options.effects.flip = options.effects.flip || (options.style == 'jo-sociallocker-secrets');

            if (options.buttons.layout == "vertical") {
                options.facebook.like.layout = "box_count";
                options.facebook.share.layout = "vertical";       
                options.twitter.count = "vertical";
                options.twitter.size = "medium";
                options.google.plus.size = "tall";
                options.google.share.annotation = "vertical-bubble";   
                options.linkedin.share.counter = "top";                   
                options.buttons.counter = true;
            }

            if (options.buttons.layout == "horizontal") {
                options.facebook.layout = "button_count";
                options.twitter.count = "horizontal";
                options.twitter.size = "medium";
                options.google.size = "medium";
                options.google.annotation = 'bubble';
                options.linkedin.share.counter = "right";   
                
                if (!options.buttons.counter) {
                    options.twitter.count = 'none';
                    options.twitter.showCount = false;        
                    options.google.annotation = 'none';
                    options.facebook.count = 'none';
                    options.linkedin.share.counter = "none";   
                }
            }
            
            if (typeof options.text != "object" || (!options.text.header && !options.text.message)) {
                options.text = { message: options.text };
            }

            if (options.text.header) {
                options.text.header = (typeof options.text.header === "function" && options.text.header(this)) ||
                                      (typeof options.text.header === "string" && $("<div>" + options.text.header + "</div>")) ||
                                      (typeof options.text.header === "object" && options.text.header.clone());
            }

            if (options.text.message) {
                options.text.message = (typeof options.text.message === "function" && options.text.message(this)) ||
                                       (typeof options.text.message === "string" && $("<div>" + options.text.message + "</div>")) ||
                                       (typeof options.text.message === "object" && options.text.message.clone());
            }

            options.locker.timer = parseInt(options.locker.timer);
            if (options.locker.timer == 0) options.locker.timer = null;

            this.options = options;
            
            // builds the css class name based on the theme name
            this.style = "jo-sociallocker-" + theme;    
        },

        /**
        * Returns true if a current user use a mobile device, else false.
        */
        _isMobile: function () {
            
            if ((/webOS|iPhone|iPod|BlackBerry/i).test(navigator.userAgent)) return true;
            if ((/Android/i).test(navigator.userAgent) && (/Mobile/i).test(navigator.userAgent)) return true;
            return false;
        },
        
        /**
        * Returns true if a current user use a mobile device or tablet device, else false.
        */
        _isTabletOrMobile: function () {
            
            if ((/webOS|iPhone|iPad|Android|iPod|BlackBerry/i).test(navigator.userAgent)) return true;
            return false;
        },
        
        /**
         * Checks whether a button is valide for the locker.
         */
        _isValidButton: function( sourceName ) {
            for ( var index in $.joextension.sociallocker.buttons ) {
                if ( $.joextension.sociallocker.buttons[index] == sourceName ) return true;;
            }
            return false;
        },

        /**
        * Sets an error state.
        */
        _setError: function (text) {
            this._error = true;
            this._errorText = text;

            this.locker && this.locker.hide();

            this.element.html("<strong>[Error]: " + text + "</strong>");
            this.element.show().addClass("jo-sociallocker-error");
        },

        // --------------------------------------------------------------------------------------
        // Markups and others.
        // --------------------------------------------------------------------------------------

        /**
        * Creates plugin markup.
        */
        _createMarkup: function () {
            var self = this;

            this.element.addClass("jo-sociallocker-content");

            var browser = (jQuery.browser.mozilla && 'mozilla') ||
                          (jQuery.browser.opera && 'opera') ||
                          (jQuery.browser.webkit && 'webkit') || 'msie';

            this.locker = $("<div class='jo-sociallocker jo-sociallocker-" + browser + "' style='display: none;'></div>");
            this.outerWrap = $("<div class='jo-sociallocker-outer-wrap'></div>").appendTo(this.locker);
            this.innerWrap = $("<div class='jo-sociallocker-inner-wrap'></div>").appendTo(this.outerWrap);
            if ( this.options.buttons.size == "fixed") this.locker.addClass("jo-sociallocker-buttons-fixed");
            this.locker.addClass(this.style);

            if (!this.options.buttons.counter) this.locker.addClass('jo-sociallocker-no-counters');
            $.joextension.isTouch()
                ? this.locker.addClass('jo-sociallocker-touch')
                : this.locker.addClass('jo-sociallocker-no-touch');

            var resultText = $("");
            if (this.options.text.header) resultText.append(this.options.text.header.addClass('jo-sociallocker-strong').clone());
            if (this.options.text.message) resultText.append(this.options.text.message.clone());

            // main locker message
            this.innerWrap.append(resultText.addClass());
            resultText.prepend(($("<div class='jo-sociallocker-before-text'></div>")));
            resultText.append(($("<div class='jo-sociallocker-after-text'></div>")));

            // creates markup for buttons
            this._createButtonMarkup();

            // bottom locker message
            this.options.bottomText && this.innerWrap.append(this.options.bottomText.addClass('jo-sociallocker-bottom-text'));

            // close button and timer if needed
            this.options.locker.close && this._createClosingCross();
            this.options.locker.timer && this._createTimer();

            var after = (this.element.parent().is('a')) ? this.element.parent() : this.element;
            this.locker.insertAfter(after);

            this._markupIsCreated = true;
        },

        /**
        * Creates markup for every social button.
        */
        _createButtonMarkup: function () {
            var self = this;
            this.buttonsWrap = $("<div class='jo-sociallocker-buttons'></div>").appendTo(this.innerWrap);
            var zIndex = 50;
            
            for (var index in this.options.buttons.order) {
                var sourceName = this.options.buttons.order[index];
                if (typeof (sourceName) != 'string') continue;

                // button separator
                if ( sourceName == '#' ) {
                    this.buttonsWrap.append("<div class='jo-button-separator'></div>");
                    continue;
                }
                
                // is button supported?

                if ( jQuery.inArray(sourceName, this.options.buttons.unsupported ) >= 0 ) {
                    var title = 'The button "' + sourceName + '" is not supported by this theme.';
                    var button = $("<div class='jo-sociallocker-button jo-sociallocker-button-unsupported'></div>");
                    var innerWrap = $("<div class='jo-sociallocker-button-inner-wrap'>" + title + "</div>").appendTo(button);
                    this.buttonsWrap.append(button);
                    continue;
                }

                var parts = sourceName.split('-');
                var networkName = parts[0];
                var buttonName = parts[1];
                var buttonFunctionName = "sociallocker_" + networkName + "_" + buttonName;

                // setup options
                var buttonOptions = $.extend({}, this.options[networkName]);
                if ( this.options[networkName][buttonName] ) buttonOptions = $.extend(buttonOptions, this.options[networkName][buttonName] );
                buttonOptions.url = buttonOptions.url || this.options.url;
       
                buttonOptions._provider = this._providers[sourceName];
                buttonOptions.unlock = function () { self._unlock("button", this._provider); };

                // creates button
                var button = $("<div class='jo-sociallocker-button jo-sociallocker-button-" + sourceName + "'></div>");
                button.addClass('jo-sociallocker-button-' + networkName);
                
                button.data('name', sourceName);
                this.buttonsWrap.append(button);

                var innerWrap = $("<div class='jo-sociallocker-button-inner-wrap'></div>").appendTo(button);
                innerWrap[buttonFunctionName](buttonOptions);

                var flipEffect = this.options.effects.flip;
                var flipSupport = $.joextension.tools.has3d();

                // addes the flip effect
                (flipEffect && flipSupport && button.addClass("jo-sociallocker-flip")) || button.addClass("jo-sociallocker-no-flip");
                if (!flipEffect) continue;
                
                // if it's a touch device
                if ($.joextension.isTouch()) {
                    
                    var overlay = ( this.options.triggers && this.options.triggers.overlayRender )
                        ? this.options.triggers.overlayRender(buttonOptions, networkName, buttonName, true)
                        : $("<a class='jo-sociallocker-button-overlay' href='#'></a>");

                    overlay.prependTo(innerWrap);
                    
                    // if it's a touch device and flip effect enabled.
                    if (flipSupport) {

                        overlay.click(function () {
                            var btn = $(this).parents('.jo-sociallocker-button');

                            if (btn.hasClass('jo-sociallocker-flip-hover')) {
                                btn.removeClass('jo-sociallocker-flip-hover');
                            } else {
                                $('.jo-sociallocker-flip-hover').removeClass('jo-sociallocker-flip-hover');
                                btn.addClass('jo-sociallocker-flip-hover');
                            }

                            return false;
                        });

                    // if it's a touch device and flip effect is not enabled.
                    } else {

                        overlay.click(function () {
                            var overlay = $(this);
                            overlay.stop().animate({ opacity: 0 }, 200, function () {
                                overlay.hide();
                            });
                            
                            return false;
                        });

                    }

                // if it's not a touch device
                } else {
                    
                    var overlay = ( this.options.triggers && this.options.triggers.overlayRender )
                        ? this.options.triggers.overlayRender(buttonOptions, networkName, buttonName, false)
                        : $("<div class='jo-sociallocker-button-overlay' href='#'></div>");

                    overlay.prependTo(innerWrap);
                    
                    if (!flipSupport) {
                        button.hover(
                            function () {
                                var overlay = $(this).find(".jo-sociallocker-button-overlay");
                                overlay.stop().animate({ opacity: 0 }, 200, function () {
                                    overlay.hide();
                                });
                            },
                            function () {
                                var overlay = $(this).find(".jo-sociallocker-button-overlay").show();
                                overlay.stop().animate({ opacity: 1 }, 200);
                            }
                        );
                    }
                }
                
                if ( overlay ) {
                    overlay.css('z-index', zIndex);
                    overlay.find('.jo-sociallocker-overlay-front').css('z-index', zIndex);
                    overlay.find('.jo-sociallocker-overlay-back').css('z-index', zIndex - 1);  
                    overlay.find('.jo-sociallocker-overlay-header').css('z-index', zIndex - 1);                  
                }
                zIndex = zIndex - 5;
            }
        },

        _makeSimilar: function (overlay, source, dontSubscrtibe) {
            var self = this;

            overlay.css({
                "width": source.outerWidth(false),
                "height": source.outerHeight(false)
            });

            if (!dontSubscrtibe) $(window).resize(function () {
                self._makeSimilar(overlay, source, true);
            });
        },

        _createClosingCross: function () {
            var self = this;

            $("<div class='jo-sociallocker-cross' title='" + $.joextension.sociallocker.lang.close + "' />")
                .prependTo(this.locker)
                .click(function () {
                    if (!self.close || !self.close(self)) self._unlock("cross", true);
                });
        },

        _createTimer: function () {

            this.timer = $("<span class='jo-sociallocker-timer'></span>");
            var timerLabelText = $.joextension.sociallocker.lang.orWait;
            var secondLabel = $.joextension.sociallocker.lang.seconds;

            this.timerLabel = $("<span class='jo-sociallocker-timer-label'>" + timerLabelText + " </span>").appendTo(this.timer);
            this.timerCounter = $("<span class='jo-sociallocker-timer-counter'>" + this.options.locker.timer + secondLabel + "</span>").appendTo(this.timer);

            this.timer.appendTo(this.locker);

            this.counter = this.options.locker.timer;
            this._kickTimer();
        },

        _kickTimer: function () {
            var self = this;

            setTimeout(function () {

                if (!self._isLocked) return;

                self.counter--;
                if (self.counter <= 0) {
                    self._unlock("timer");
                } else {
                    self.timerCounter.text(self.counter + $.joextension.sociallocker.lang.seconds);

                    // Opera fix.
                    if ($.browser.opera) {
                        var box = self.timerCounter.clone();
                        box.insertAfter(self.timerCounter);
                        self.timerCounter.remove();
                        self.timerCounter = box;
                    }

                    self._kickTimer();
                }
            }, 500);
        },

        // --------------------------------------------------------------------------------------
        // Lock/Unlock content.
        // --------------------------------------------------------------------------------------

        _lock: function (typeSender, sender) {

            if (this._isLocked || this._stoppedByWatchdog) return;
            if (!this._markupIsCreated) this._createMarkup();

            if (typeSender == "button") sender.setState("locked");

            this.element.hide();
            this.isInline ? this.locker.css("display", "inline-block") : this.locker.fadeIn(1000);

            this._isLocked = true;
            if (this.options.events.lock) this.options.events.lock(typeSender, sender && sender.name);
        },

        _unlock: function (typeSender, sender) {
            var self = this;

            if (!this._isLocked) { this._showContent(true); return false; }
            if (typeSender == "button") sender.setState("unlocked");

            this._showContent(true);

            this._isLocked = false;
            if (typeSender == "timer" && this.options.events.unlockByTimer) return this.options.events.unlockByTimer();
            if (typeSender == "close" && this.options.events.unlockByClose) return this.options.events.unlockByClose();
            if (this.options.events.unlock) this.options.events.unlock(typeSender, sender && sender.name);
        },

        lock: function () {
            this._lock("user");
        },

        unlock: function () {
            this._unlock("user");
        },

        _showContent: function (useEffects) {
            var self = this;

            var effectFunction = function () {
                if (self.locker) self.locker.hide();
                if (!useEffects) { self.element.show(); return; }

                self.element.fadeIn(500, function () {
                    self.options.effects.highlight && self.element.effect && self.element.effect('highlight', { color: '#fffbcc' }, 500);
                });
            };

            if (!this.options.content) {
                effectFunction();

            } else if (typeof this.options.content === "string") {
                this.element.html(this.options.content);
                effectFunction();

            } else if (typeof this.options.content === "object" && !this.options.content.url) {
                this.element.append(this.options.content.clone().show());
                effectFunction();

            } else if (typeof this.options.content === "object" && this.options.content.url) {

                var ajaxOptions = $.extend(true, {}, this.options.content);

                var customSuccess = ajaxOptions.success;
                var customComplete = ajaxOptions.complete;
                var customError = ajaxOptions.error;

                ajaxOptions.success = function (data, textStatus, jqXHR) {

                    !customSuccess ? self.element.html(data) : customSuccess(self, data, textStatus, jqXHR);
                    effectFunction();
                };

                ajaxOptions.error = function (jqXHR, textStatus, errorThrown) {

                    self._setError("An error is triggered during the ajax request! Text: " + textStatus + " " + errorThrown);
                    customError && customError(jqXHR, textStatus, errorThrown);
                };

                ajaxOptions.complete = function (jqXHR, textStatus) {

                    customComplete && customComplete(jqXHR, textStatus);
                };

                $.ajax(ajaxOptions);

            } else {
                effectFunction();
            }
        }
    });

    /**
    * [obsolete]
    */
    $.fn.socialLock = function (opts) {

        opts = $.extend({}, opts);
        $(this).sociallocker(opts);
    };

})(jQuery);;// -
    // Notice: read the documentation that comes with the plugins to get more details 
