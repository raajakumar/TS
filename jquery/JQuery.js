/*

 *version: 0.03  *Aurthor: Pradab.v

 */

/*updated change year,change month in datepicker*/

//From August 2014 Updates
/*
 1)Form Title will be Focused  when u click Ok button which contain "messageclose" Class (Automatic-no code required).
 2)Pre Loader will appear Near the button where u clicked , or else u can target any elements its will appear near that element
 For current element -  resetPreloader($(this).position());
 To Target any other element -  resetPreloader($('#ElementId').position());
 add above code below $(".preloader").show();  line.
 3)Msg Box and Confirm Box will appear next to target element.
 For current element -
 $(document).doValidation({rule:'messagebox',prop:{msgtitle:'Title',msgcontent:'Message',confirmation:true,position:$(this).position()}});
 To Target any other element -
 $(document).doValidation({rule:'messagebox',prop:{msgtitle:'Title',msgcontent:'Message',confirmation:true,position:$('#ElementId').position()}});



 */

(function (c) {

    c.fn.doValidation = function (n) {

        var t = 189;

        c.browser.mozilla && (t = 173);

        n = c.extend({}, c.fn.doValidation.defaults, n);

        var d = n.prop,

            h = n.rule,

            p = d.realpart,

            f = d.imaginary,

            A = d.uppercase,

            B = d.integer,

            u = d.autosize,

            q = d.whitespace,

            v = d.leadzero,

            w = d.elem1,

            x = d.elem2,

            y = d.allowdot,

            r = d.smallerthan,

            s = d.greaterthan,

            C = d.msgtitle,

            D = d.msgcontent,

            E = d.confirmation,

            pos = d.position,

            z = t,

            l = 190,

            m = z;

        return this.each(function () {

            if ("fromto" === h || "startend" === h) c("#" + x).datepicker({

                dateFormat: "dd-mm-yy",

                minDate: new Date(1997, 0 , 2),

                changeYear: true,

                changeMonth: true

            }), c("#" + w).datepicker({

                dateFormat: "dd-mm-yy",

                minDate: new Date(1997, 0 , 2),

                changeYear: true,

                changeMonth: true,

                onSelect: function (a) {

                    a = c("#" + w).datepicker("getDate");

                    a = new Date(Date.parse(a));

                    "fromto" === h ? a.setDate(a.getDate()) : a.setDate(a.getDate() + 1);

                    a = a.toDateString();

                    a = new Date(Date.parse(a));

                    c("#" + x).datepicker("option", "minDate", a)

                    // var mxdate=a.setDate(a.getDate()+'1y');
                    // mxdate=mxdate.toDateString();
                    // mxdate = new Date(Date.parse(mxdate));

                    // c("#" + x).datepicker("option", "maxDate", mxdate);

                }

            });

            if ("messagebox" === h) {

                c("#Messagebox").length || c("body").append('<div style="display:none;" id="Messagebox" title=""></div>');

                var d = [{

                    text: "OK",

                    "class": "msgbtn messageclose",

                    click: function () {

                        c(this).dialog("close")

                    }

                }];

                !0 === E && (d = [{

                    text: "OK",

                    "class": "msgbtn messageconfirm",

                    click: function () {

                        c(this).dialog("close")

                    }

                }, {

                    text: "CANCEL",

                    "class": "msgbtn messagecancel",

                    click: function () {

                        c(this).dialog("close")

                    }

                }]);

                c("#Messagebox").dialog({

                    resizable: !1,

                    // position:[$(window).width() / 2 - 2000, 50],
                    position:[0, 50],


                    hide: {

                        effect: "fadeOut",

                        duration: 300

                    },
                    width: 340,
                    height: 160,

                    modal: !0,

                    title: C,

                    buttons: d

                }).html("<br>" + "<b>"+D+"</b>");

                if(typeof pos == 'object')
                    $(".ui-dialog").css({top:pos.top, left:pos.left})
                $(".ui-dialog").focus();

                c(".ui-dialog-titlebar").addClass("MessageBoxTitle")

            }

            c(this).keydown(function (a) {

                var b = a.which,

                    d = String.fromCharCode(b),

                    e = c(this).val();

                switch (h) {

                    case "alphabets":

                        e = void 0 !== q && !0 === q && 0 < e.length ? 32 : 8;

                        if ((d.match(/[a-zA-Z]/) || 8 === b || 37 === b || 39 === b || 35 === b || 36 === b || 9 === b || 13 === b || 20 === b || b === e || 65 === b && !0 === a.ctrlKey || 46 === b) && !1 === a.shiftKey) break;

                        else a.preventDefault();

                        break;

                    case "alphanumeric":

                        var g = void 0 != y && !0 == y ? 190 : 8,

                            k = e.indexOf(".");

                        8 != g && (g = -1 == k && 1 == e.length ? g : 8);

                        e = void 0 != q && !0 == q && 0 < e.length ? 32 : 8;

                        if (!d.match(/\w/) && 8 != b && 9 != b && 37 != b && 39 != b && 35 != b && 36 != b && 13 != b && 20 != b && b != e && b != g && 46 != b || !1 != a.shiftKey) a.preventDefault();

                        else break;

                        break;

                    case "numbersonly":

                        g = void 0 != p && void 0 != f ? p + f : p, m = void 0 != B ? z : 8, k = e.lastIndexOf("-"), 8 != m && (m = 0 > k && 1 > e.length ? m : 8), g = 0 != k ? g : g + 1, c(this).attr("maxlength", g), l = void 0 != f && 0 != f ? 190 : 8, k = e.indexOf("."), 8 != l && (l = 0 > k ? l : 8), e = 0 > k ? g - 1 : g + 1, void 0 != f && 0 != f && c(this).attr("maxlength", e), (!d.match(/^\d/) && b != m && b != l && 37 != b && 39 != b && 35 != b && 36 != b && 9 != b && 8 != b && 13 != b && 20 != b && 46 != b || !1 != a.shiftKey) && a.preventDefault()

                }

            });

            c(this).keyup(function (a) {

                String.fromCharCode(a.which);

                var b = c(this).val();

                if (void 0 !== u && !0 === u) {

                    var d = parseInt(c(this).val().length) + 10,

                        d = 20 > d ? 20 : d;

                    c(this).attr({

                        size: d

                    })

                }

                if (46 != a.keyCode && 8 != a.keyCode && (35 > a.keyCode || 40 < a.keyCode)) switch (!1 != A && "numbersonly" != h && (this.value = this.value.toUpperCase()), h) {

                    case "numbersonly":

                        if (!0 == f || void 0 != f) d = parseInt(p) + ("-" != b.charAt(0) ? 0 : 1), d < b.length && "." != b.charAt(b.length - 1) && 1 > b.indexOf(".") && c(this).val(b.substr(0, b.length - 1))

                }

            });

            c(this).blur(function (a) {

                switch (h) {

                    case "email":

                        a = c.trim(c(this).val().toString());

                        if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(a) || "" == a) ErrorControl.EmailId = "Valid";

                        else return ErrorControl.EmailId = "InValid", !1;

                        break;

                    case "numbersonly":

                        a = c(this).val();

                        if (!0 !== v || void 0 === v)

                            if (void 0 != f && 0 != f)

                                if ("" == a || "-" === a) c(this).val("");

                                else if (0 != parseFloat(a)) {

                                    var b = a.indexOf("."),

                                        d = a.substr(a.indexOf(".")).toString().length; - 1 == b && c(this).val(parseInt(a) + ".00");

                                    0 < b && 2 == d && c(this).val(a + "0");

                                    0 < b && 1 == d && c(this).val(parseInt(a) + ".00");

                                    0 == b && 2 == d && c(this).val("0" + a + "0");

                                    0 == b && 2 < d && c(this).val(parseFloat(a));

                                    a = c(this).val();

                                    "." == a ? c(this).val("") : c(this).val(0 == parseFloat(a).toFixed(2) ? "" : parseFloat(a).toFixed(2))

                                } else c(this).val("");

                            else {

                                if (void 0 == f || 0 == f) "" == a || "-" === a ? c(this).val("") : 0 != parseInt(a) ? c(this).val(parseInt(a)) : c(this).val("")

                            } else 0 == parseInt(a) && c(this).val("");

                        void 0 != r && "" != r && (a = parseFloat(c("#" + r).val()), b = parseFloat(c(this).val()), ErrorControl.AmountCompare = b <= a && (0 < a || 0 > a) ? "Valid" : "InValid");

                        void 0 != s && "" != s && (a = parseFloat(c(this).val()), b = parseFloat(c("#" + s).val()), ErrorControl.AmountCompare = a >= b && (0 < b || 0 > b) ? "Valid" : "InValid")

                }

                a = c(this).val();

                c(this).val(c.trim(a).replace(/\s\s+/g, " "))

            })

        })

    };

    c.fn.doValidation.defaults = {

        rule: "General",

        prop: "General"

    }

})(jQuery);
//date validation for mandatory field
$(document).on('keydown keypress','.datemandtry',function(e) {
    e.preventDefault();
});
//date validation for non mandatory field
$(document).on('keydown keypress','.datenonmandtry',function(e) {
    if (46 != e.keyCode && 8 != e.keyCode)
    {
        e.preventDefault();
    }
    if(46 == e.keyCode||8 == e.keyCode)
    {
        $(this).val('');
    }

});

$(document).on('keyup','.numberonlycommas',function(e) {
    var val = $(this).val();
    if($.trim($.isNumeric(val))=="false")
    {
        $(this).val(val.replace(/[^0-9,]+/g,''));
    }

    if(val.substring(0,1)===',')
    {
        $(this).val(val.substring(1));
    }
});
$(document).on('keyup','.numonlynozero',function(e) {
    var val = $(this).val();
    if($.trim($.isNumeric(val))=="false")
    {
        $(this).val(val.replace(/\D/g,''));
    }

    if(val.substring(0,1)==='0')
    {
        $(this).val(val.substring(1));
    }

});
$(document).on('keyup','.alnumonlynozero',function(e) {
    var val = $(this).val();
    var letters = /^[a-zA-Z0-9]+$/;
    if(!letters.test(val))
    {
        $(this).val(val.replace(/[^a-zA-Z 0-9]+/g,''));
    }
    if(val.substring(0,1)==='0')
    {
        $(this).val(val.substring(1));
    }

});

$(document).on('keyup','.alnumonlyzero',function(e) {
    var val = $(this).val();
    var letters = /^[a-zA-Z0-9]+$/;
    if(!letters.test(val))
    {
        $(this).val(val.replace(/[^a-zA-Z 0-9]+/g,''));
    }

});
//pass in just the context as a $(obj) or a settings JS object
$.fn.autogrow = function(opts) {
    var that = $(this).css({overflow: 'hidden', resize: 'none'}) //prevent scrollies
        , selector = that.selector
        , defaults = {
            context: $(document) //what to wire events to
            , animate: true //if you want the size change to animate
            , speed: 200 //speed of animation
            , fixMinHeight: true //if you don't want the box to shrink below its initial size
            , cloneClass: 'autogrowclone' //helper CSS class for clone if you need to add special rules
            , onInitialize: false //resizes the textareas when the plugin is initialized
        }
        ;
    opts = $.isPlainObject(opts) ? opts : {context: opts ? opts : $(document)};
    opts = $.extend({}, defaults, opts);
    that.each(function(i, elem){
        var min, clone;
        elem = $(elem);
        //if the element is "invisible", we get an incorrect height value
        //to get correct value, clone and append to the body.
        if (elem.is(':visible') || parseInt(elem.css('height'), 10) > 0) {
            min = parseInt(elem.css('height'), 10) || elem.innerHeight();
        } else {
            clone = elem.clone()
                .addClass(opts.cloneClass)
                .val(elem.val())
                .css({
                    position: 'absolute'
                    , visibility: 'hidden'
                    , display: 'block'
                })
            ;
            $('body').append(clone);
            min = clone.innerHeight();
            clone.remove();
        }
        if (opts.fixMinHeight) {
            elem.data('autogrow-start-height', min); //set min height
        }
        elem.css('height', min);

        if (opts.onInitialize && elem.length) {
            resize.call(elem[0]);
        }
    });
    opts.context
        .on('keyup paste', selector, resize)
    ;

    function resize (e){
        var box = $(this)
            , oldHeight = box.innerHeight()
            , newHeight = this.scrollHeight
            , minHeight = box.data('autogrow-start-height') || 0
            , clone
            ;
        if (oldHeight < newHeight) { //user is typing
            this.scrollTop = 0; //try to reduce the top of the content hiding for a second
            opts.animate ? box.stop().animate({height: newHeight}, opts.speed) : box.innerHeight(newHeight);
        } else if (!e || e.which == 8 || e.which == 46 || (e.ctrlKey && e.which == 88)) { //user is deleting, backspacing, or cutting
            if (oldHeight > minHeight) { //shrink!
                //this cloning part is not particularly necessary. however, it helps with animation
                //since the only way to cleanly calculate where to shrink the box to is to incrementally
                //reduce the height of the box until the $.innerHeight() and the scrollHeight differ.
                //doing this on an exact clone to figure out the height first and then applying it to the
                //actual box makes it look cleaner to the user
                clone = box.clone()
                    //add clone class for extra css rules
                    .addClass(opts.cloneClass)
                    //make "invisible", remove height restriction potentially imposed by existing CSS
                    .css({position: 'absolute', zIndex:-10, height: ''})
                    //populate with content for consistent measuring
                    .val(box.val())
                ;
                box.after(clone); //append as close to the box as possible for best CSS matching for clone
                do { //reduce height until they don't match
                    newHeight = clone[0].scrollHeight - 1;
                    clone.innerHeight(newHeight);
                } while (newHeight === clone[0].scrollHeight);
                newHeight++; //adding one back eliminates a wiggle on deletion
                clone.remove();
                box.focus(); // Fix issue with Chrome losing focus from the textarea.

                //if user selects all and deletes or holds down delete til beginning
                //user could get here and shrink whole box
                newHeight < minHeight && (newHeight = minHeight);
                oldHeight > newHeight && opts.animate ? box.stop().animate({height: newHeight}, opts.speed) : box.innerHeight(newHeight);
            } else { //just set to the minHeight
                box.innerHeight(minHeight);
            }
        }
    }
    return that;
}

function resetPreloader(pos){
    $(".statusarea").css({marginTop:pos.top+'px', marginLeft:pos.left+'px'});
}
function adjustPosition(oldPosition,top,left){
    var newTop=oldPosition.top+top;
    var newLeft=oldPosition.left+left;
    return {top:newTop,left:newLeft};
}

$(function() {

    $(document).on('click','.messageclose',function(){
        $('#focustext').focus();
    });

    if(!$('#focustext').length)
        $('#fhead').append('<input type="button" id="focustext" style="border:none;  position:relative; top:-200px;  z-index:-50;" />');
});


function replaceSpclcharAngularBrack(str)
{
    var finalstr = str.replace(/</g, "&lt;");
    finalstr = finalstr.replace(/>/g, "&gt;");
    return finalstr;
}