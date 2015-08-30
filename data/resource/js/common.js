function drop_confirm(t, a) {
    if (confirm(t)) {
        window.location = a
    }
}
function ajax_confirm(t, a) {
    if (confirm(t)) {
        ajaxget(a)
    }
}
function go(t) {
    window.location = t
}
function price_format(t) {
    if (typeof PRICE_FORMAT == "undefined") {
        PRICE_FORMAT = "&yen;%s"
    }
    t = number_format(t, 2);
    return PRICE_FORMAT.replace("%s", t)
}
function number_format(t, a) {
    if (a < 0) {
        return t
    }
    t = Number(t);
    if (isNaN(t)) {
        t = 0
    }
    var e = t.toString();
    var i = e.split(".");
    var n = i[0];
    var o = i[1];
    if (e.indexOf(".") == -1) {
        if (a == 0) {
            return e
        }
        var d = "";
        for (var l = 0; l < a; l++) {
            d += "0"
        }
        e = e + "." + d
    } else {
        if (o.length == a) {
            return e
        }
        if (o.length > a) {
            e = e.substr(0, e.length - (o.length - a));
            if (a == 0) {
                e = n
            }
        } else {
            for (var l = 0; l < a - o.length; l++) {
                e += "0"
            }
        }
    }
    return e
}
function getFullPath(t) {
    if (t) {
        if (window.navigator.userAgent.indexOf("MSIE") >= 1) {
            t.select();
            if (window.navigator.userAgent.indexOf("MSIE") == 25) {
                t.blur()
            }
            return document.selection.createRange().text
        } else if (window.navigator.userAgent.indexOf("Firefox") >= 1) {
            if (t.files) {
                return window.URL.createObjectURL(t.files.item(0))
            }
            return t.value
        }
        return t.value
    }
}
function transform_char(t) {
    if (t.indexOf("&")) {
        t = t.replace(/&/g, "%26")
    }
    return t
} (function(t) {
    t.fn.VMiddleImg = function(a) {
        var e = {
            width: null,
            height: null
        };
        var i = t.extend({},
        e, a);
        return t(this).each(function() {
            var a = t(this);
            var e = a.height();
            var n = a.width();
            var o = i.height || a.parent().height();
            var d = i.width || a.parent().width();
            var l = e / n;
            if (e > o && n > d) {
                if (e > n) {
                    a.width(d);
                    a.height(d * l)
                } else {
                    a.height(o);
                    a.width(o / l)
                }
                e = a.height();
                n = a.width();
                if (e > n) {
                    a.css("top", (o - e) / 2)
                } else {
                    a.css("left", (d - n) / 2)
                }
            } else {
                if (n > d) {
                    a.css("left", (d - n) / 2)
                }
                a.css("top", (o - e) / 2)
            }
        })
    }
})(jQuery);
function DrawImage(t, a, e) {
    var i = new Image;
    i.src = t.src;
    if (i.width > 0 && i.height > 0) {
        if (i.width / i.height >= a / e) {
            if (i.width > a) {
                t.width = a;
                t.height = i.height * a / i.width
            } else {
                t.width = i.width;
                t.height = i.height
            }
        } else {
            if (i.height > e) {
                t.height = e;
                t.width = i.width * e / i.height
            } else {
                t.width = i.width;
                t.height = i.height
            }
        }
    }
}
function showTips(t, a, e) {
    var i = document.documentElement.clientWidth;
    var n = '<div class="tipsClass">' + t + "</div>";
    $("body").append(n);
    $("div.tipsClass").css({
        top: 200 + "px",
        left: i / 2 - t.length * 13 / 2 + "px",
        position: "fixed",
        padding: "20px 50px",
        background: "#EAF2FB",
        "font-size": 14 + "px",
        margin: "0 auto",
        "text-align": "center",
        width: "auto",
        color: "#333",
        border: "solid 1px #A8CAED",
        opacity: "0.90",
        "z-index": "9999"
    }).show();
    setTimeout(function() {
        $("div.tipsClass").fadeOut().remove()
    },
    e * 1e3)
}
function trim(t) {
    return (t + "").replace(/(\s+)$/g, "").replace(/^\s+/g, "")
}
function login_dialog() {
    CUR_DIALOG = ajax_form("login", "登录", SITEURL + "/index.php?act=login&inajax=1", 360, 1)
}
function ajax_form(t, a, e, i, n) {
    if (!i) i = 480;
    if (!n) n = 1;
    var o = DialogManager.create(t);
    o.setTitle(a);
    o.setContents("ajax", e);
    o.setWidth(i);
    o.show("center", n);
    return o
}
function html_form(t, a, e, i, n) {
    if (!i) i = 480;
    if (!n) n = 0;
    var o = DialogManager.create(t);
    o.setTitle(a);
    o.setContents(e);
    o.setWidth(i);
    o.show("center", n);
    return o
}
function collect_store(t, a, e) {
    $.get("index.php?act=index&op=login",
    function(i) {
        if (i == "0") {
            login_dialog()
        } else {
            var n = "index.php?act=member_favorites&op=favoritesstore";
            $.getJSON(n, {
                fid: t
            },
            function(i) {
                if (i.done) {
                    showDialog(i.msg, "succ", "", "", "", "", "", "", "", "", 2);
                    if (a == "count") {
                        $('[nctype="' + e + '"]').each(function() {
                            $(this).html(parseInt($(this).text()) + 1)
                        })
                    }
                    if (a == "succ") {
                        $('[nctype="' + e + '"]').each(function() {
                            $(this).html("收藏成功")
                        })
                    }
                    if (a == "store") {
                        $('[nc_store="' + t + '"]').each(function() {
                            $(this).before('<span class="goods-favorite" title="该店铺已收藏"><i class="have">&nbsp;</i></span>');
                            $(this).remove()
                        })
                    }
                } else {
                    showDialog(i.msg, "notice")
                }
            })
        }
    })
}
function collect_goods(t, a, e) {
    $.get("index.php?act=index&op=login",
    function(i) {
        if (i == "0") {
            login_dialog()
        } else {
            var n = "index.php?act=member_favorites&op=favoritesgoods";
            $.getJSON(n, {
                fid: t
            },
            function(t) {
                if (t.done) {
                    showDialog(t.msg, "succ", "", "", "", "", "", "", "", "", 2);
                    if (a == "count") {
                        $('[nctype="' + e + '"]').each(function() {
                            $(this).html(parseInt($(this).text()) + 1)
                        })
                    }
                    if (a == "succ") {
                        $('[nctype="' + e + '"]').each(function() {
                            $(this).html("收藏成功")
                        })
                    }
                } else {
                    showDialog(t.msg, "notice")
                }
            })
        }
    })
}
function load_cart_information() {
    $.getJSON(SITEURL + "/index.php?act=cart&op=ajax_load&callback=?",
    function(t) {
        var a = $(".head-user-menu .my-cart");
        if (t) {
            var e = "";
            if (t.cart_goods_num > 0) {
                for (var i = 0; i < t["list"].length; i++) {
                    var n = t["list"][i];
                    e += '<dl ncTpye="cart_item_' + n["cart_id"] + '"><dt class="goods-name"><a href="' + n["goods_url"] + '">' + n["goods_name"] + "</a></dt>";
                    e += '<dd class="goods-thumb"><a href="' + n["goods_url"] + '" title="' + n["goods_name"] + '"><img src="' + n["goods_image"] + '"></a></dd>';
                    e += '<dd class="goods-sales"></dd>';
                    e += '<dd class="goods-price"><em>&yen;' + n["goods_price"] + "×" + n["goods_num"] + "</dd>";
                    e += '<dd class="handle"><a href="javascript:void(0);" onClick="drop_topcart_item(' + n["cart_id"] + ');">删除</a></dd>';
                    e += "</dl>"
                }
                a.find(".incart-goods").html(e);
                a.find(".incart-goods-box").perfectScrollbar("destroy");
                a.find(".incart-goods-box").perfectScrollbar();
                e = "共<i>" + t.cart_goods_num + "</i>种商品&nbsp;&nbsp;总计金额：<em>&yen;" + t.cart_all_price + "</em>";
                a.find(".total-price").html(e);
                if (a.find(".addcart-goods-num").size() == 0) {
                    a.append('<div class="addcart-goods-num">0</div>')
                }
                a.find(".addcart-goods-num").html(t.cart_goods_num);
                $("#rtoobar_cart_count").html(t.cart_goods_num).show()
            } else {
                e = "<div class='no-order'><span>您的购物车中暂无商品，赶快选择心爱的商品吧！</span></div>";
                a.find(".incart-goods").html(e);
                a.find(".total-price").html("");
                $(".addcart-goods-num").remove();
                $("#rtoobar_cart_count").html("").hide()
            }
        }
    })
}
function drop_topcart_item(t) {
    $.getJSON(SITEURL + "/index.php?act=cart&op=del&cart_id=" + t + "&callback=?",
    function(a) {
        if (a.state) {
            var e = $(".head-user-menu .my-cart");
            if (a.quantity == 0) {
                html = "<div class='no-order'><span>您的购物车中暂无商品，赶快选择心爱的商品吧！</span></div>";
                e.find(".incart-goods").html(html);
                e.find(".total-price").html("");
                e.find(".addcart-goods-num").remove();
                $(".cart-list").html('<li><dl><dd style="text-align: center; ">暂无商品</dd></dl></li>');
                $('div[ncType="rtoolbar_total_price"]').html("");
                $("#rtoobar_cart_count").html("").hide()
            } else {
                $('dl[ncTpye="cart_item_' + t + '"]').remove();
                $('li[ncTpye="cart_item_' + t + '"]').remove();
                html = "共<i>" + a.quantity + "</i>种商品&nbsp;&nbsp;总计金额：<em>&yen;" + a.amount + "</em>";
                e.find(".total-price").html(html);
                e.find(".addcart-goods-num").html(a.quantity);
                e.find(".incart-goods-box").perfectScrollbar("destroy");
                e.find(".incart-goods-box").perfectScrollbar();
                $('div[ncType="rtoolbar_total_price"]').html("共计金额：<em class='goods-price'>&yen;" + a.amount + "</em>");
                $("#rtoobar_cart_count").html(a.quantity);
                if ($("#rtoolbar_cartlist > ul").children().size() != a.quantity) {
                    $("#rtoolbar_cartlist").load("index.php?act=cart&op=ajax_load&type=html");
                    return
                }
            }
        } else {
            alert(a.msg)
        }
    })
}
function load_history_information() {
    $.getJSON(SITEURL + "/index.php?act=index&op=viewed_info",
    function(t) {
        var a = $(".head-user-menu .my-mall");
        if (t["m_id"] > 0) {
            if (typeof t["consult"] !== "undefined") a.find("#member_consult").html(t["consult"]);
            if (typeof t["consult"] !== "undefined") a.find("#member_voucher").html(t["voucher"])
        }
        var e = 0;
        var i = "";
        var n = 0;
        if (typeof t["viewed_goods"] !== "undefined") {
            for (e in t["viewed_goods"]) {
                var o = t["viewed_goods"][e];
                i += '<li class="goods-thumb"><a href="' + o["url"] + '" title="' + o["goods_name"] + '" target="_blank"><img src="' + o["goods_image"] + '" alt="' + o["goods_name"] + '"></a>';
                i += "</li>";
                n++;
                if (n > 4) break
            }
        }
        if (i == "") i = '<li class="no-goods">暂无商品</li>';
        a.find(".browse-history ul").html(i)
    })
} (function(t) {
    t.show_nc_login = function(a) {
        var e = t.extend({},
        {
            action: "/index.php?act=login&op=login&inajax=1",
            nchash: "",
            formhash: "",
            anchor: ""
        },
        a);
        var i = t('<div class="quick-login"></div>');
        var n = document.location.href;
        i.append('<form class="bg" method="post" id="ajax_login" action="' + APP_SITE_URL + e.action + '"></form>').find("form").append('<input type="hidden" value="ok" name="form_submit">').append('<input type="hidden" value="' + e.formhash + '" name="formhash">').append('<input type="hidden" value="' + e.nchash + '" name="nchash">').append('<dl><dt>用户名</dt><dd><input type="text" name="user_name" autocomplete="off" class="text"></dd></dl>').append('<dl><dt>密&nbsp;&nbsp;&nbsp;码</dt><dd><input type="password" autocomplete="off" name="password" class="text"></dd></dl>').append('<dl><dt>验证码</dt><dd><input type="text" size="10" maxlength="4" class="text fl w60" name="captcha"><img border="0" onclick="this.src=\'' + APP_SITE_URL + "/index.php?act=seccode&amp;op=makecode&amp;nchash=" + e.nchash + '&amp;t=\' + Math.random()" name="codeimage" title="看不清，换一张" src="' + APP_SITE_URL + "/index.php?act=seccode&amp;op=makecode&amp;nchash=" + e.nchash + '" class="fl ml10"><span>不区分大小写</span></dd></dl>').append('<ul><li>›&nbsp;如果您没有登录账号，请先<a class="register" href="' + SHOP_SITE_URL + '/index.php?act=login&amp;op=register">注册会员</a>然后登录</li><li>›&nbsp;如果您<a class="forget" href="' + SHOP_SITE_URL + '/index.php?act=login&amp;op=forget_password">忘记密码</a>？，申请找回密码</li></ul>').append('<div class="enter"><input type="submit" name="Submit" value="&nbsp;" class="submit"></div><input type="hidden" name="ref_url" value="' + n + '">');
        i.find('input[type="submit"]').click(function() {
            ajaxpost("ajax_login", "", "", "onerror")
        });
        html_form("form_dialog_login", "登录", i, 360)
    };
    t.fn.nc_login = function(a) {
        return this.each(function() {
            t(this).on("click",
            function() {
                t.show_nc_login(a);
                return false
            })
        })
    }
})(jQuery); (function(t) {
    t.fn.nc_placeholder = function() {
        var a = "placeholder" in document.createElement("input");
        return this.each(function() {
            if (!a) {
                $el = t(this);
                $el.focus(function() {
                    if ($el.attr("placeholder") === $el.val()) {
                        $el.val("");
                        $el.attr("data-placeholder", "")
                    }
                }).blur(function() {
                    if ($el.val() === "") {
                        $el.val($el.attr("placeholder"));
                        $el.attr("data-placeholder", "placeholder")
                    }
                }).blur()
            }
        })
    }
})(jQuery); (function(t) {
    t.fn.nc_show_dialog = function(a) {
        var e = t(this);
        var i = t.extend({},
        {
            width: 480,
            title: "",
            close_callback: function() {}
        },
        a);
        var n = function(a) {
            var n = e;
            e.addClass("dialog_wrapper");
            e.wrapInner(function() {
                return '<div class="dialog_content">'
            });
            e.wrapInner(function() {
                return '<div class="dialog_body" style="position: relative;">'
            });
            e.find(".dialog_body").prepend('<h3 class="dialog_head" style="cursor: move;"><span class="dialog_title"><span class="dialog_title_icon">' + i.title + '</span></span><span class="dialog_close_button">X</span></h3>');
            e.append('<div style="clear:both;"></div>');
            t(".dialog_close_button").click(function() {
                i.close_callback();
                n.hide()
            });
            e.draggable({
                handle: ".dialog_head"
            })
        };
        if (!t(this).hasClass("dialog_wrapper")) {
            n(i.title)
        }
        i.left = t(window).scrollLeft() + (t(window).width() - i.width) / 2;
        i.top = (t(window).height() - t(this).height()) / 2;
        t(this).attr("style", "display:none; z-index: 1100; position: fixed; width: " + i.width + "px; left: " + i.left + "px; top: " + i.top + "px;");
        t(this).show()
    }
})(jQuery); (function($) {
    $.fn.membershipCard = function(options) {
        var defaults = {
            type: ""
        };
        options = $.extend(defaults, options);
        return this.each(function() {
            var $this = $(this);
            var data_str = $(this).attr("data-param");
            eval("data_str = " + data_str);
            var _uri = SITEURL + "/index.php?act=member_card&callback=?&uid=" + data_str.id + "&from=" + options.type;
            var _dl = "";
            $this.qtip({
                content: {
                    text: "Loading...",
                    ajax: {
                        url: _uri,
                        type: "GET",
                        dataType: "jsonp",
                        success: function(data) {
                            if (data) {
                                _dl = $("<dl></dl>");
                                _dttmp = $('<dt class="member-id"></dt>');
                                _dttmp.append('<i class="sex' + data.sex + '"></i>').append('<a href="' + SHOP_SITE_URL + "/index.php?act=member_snshome&mid=" + data.id + '" target="_blank">' + data.name + "</a>" + (data.truename != "" ? "(" + data.truename + ")": ""));
                                if (options.type == "shop") {
                                    _dttmp.append(data.level_name ? '&nbsp;<div class="nc-grade-mini">' + data.level_name + "</div>": "")
                                }
                                _dttmp.appendTo(_dl);
                                $('<dd class="avatar"><a href="' + SHOP_SITE_URL + "/index.php?act=member_snshome&mid=" + data.id + '" target="_blank"><img src="' + data.avatar + '" /></a><dd>').appendTo(_dl);
                                var _info = "";
                                if (typeof connect !== "undefined" && connect === 1 && data.follow != 2) {
                                    var class_html = "chat_offline";
                                    var text_html = "离线";
                                    if (typeof user_list[data.id] !== "undefined" && user_list[data.id]["online"] > 0) {
                                        class_html = "chat_online";
                                        text_html = "在线"
                                    }
                                    _info += '<a class="chat ' + class_html + '" title="点击这里给我发消息" href="JavaScript:chat(' + data.id + ');">' + text_html + "</a>"
                                }
                                if (data.qq != "") {
                                    _info += '<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=' + data.qq + '&site=qq&menu=yes" title="QQ: ' + data.qq + '"><img border="0" src="http://wpa.qq.com/pa?p=2:' + data.qq + ':52" style=" vertical-align: middle;"/></a>'
                                }
                                if (data.ww != "") {
                                    _info += '<a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=' + data.ww + "&site=cntaobao&s=1&charset=" + _CHARSET + '" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=' + data.ww + "&site=cntaobao&s=2&charset=" + _CHARSET + '" alt="点击这里给我发消息" style=" vertical-align: middle;"/></a>'
                                }
                                if (_info == "") {
                                    _info = "--"
                                }
                                var _ul = $("<ul></ul>").append("<li>城市：" + (data.areainfo != null ? data.areainfo: "--") + "</li>").append("<li>生日：" + (data.birthday != null ? data.birthday: "--") + "</li>").append("<li>联系：" + _info + "</li>").appendTo('<dd class="info"></dd>').parent().appendTo(_dl);
                                if (data.url != "") {
                                    $.getJSON(data.url + "/index.php?act=member_card&op=mcard_info&uid=" + data.id,
                                    function(d) {
                                        if (d) {
                                            eval("var msg = " + options.type + "_function(d);");
                                            msg.appendTo(_dl)
                                        }
                                    });
                                    data.url = ""
                                }
                                var _bottom;
                                if (data.follow != 2) {
                                    _bottom = $('<div class="bottom"></div>');
                                    var _a;
                                    if (data.follow == 1) {
                                        $('<div class="follow-handle" nctype="follow-handle' + data.id + "\" data-param=\"{'mid':" + data.id + '}"></div>').append('<a href="javascript:void(0);" >已关注</a>').append('<a href="javascript:void(0);" nctype="nofollow">取消关注</a>').find('a[nctype="nofollow"]').click(function() {
                                            onfollow($(this))
                                        }).end().appendTo(_bottom)
                                    } else {
                                        $('<div class="follow-handle" nctype="follow-handle' + data.id + "\" data-param=\"{'mid':" + data.id + '}"></div>').append('<a href="javascript:void(0);" nctype="follow">加关注</a>').find('a[nctype="follow"]').click(function() {
                                            follow($(this))
                                        }).end().appendTo(_bottom)
                                    }
                                    $('<div class="send-msg"> <a href="' + SHOP_SITE_URL + "/index.php?act=member_message&op=sendmsg&member_id=" + data.id + '" target="_blank"><i></i>站内信</a> </div>').appendTo(_bottom)
                                }
                                var _content = $('<div class="member-card"></div>').append(_dl).append(_bottom);
                                this.set("content.text", " ");
                                this.set("content.text", _content)
                            }
                        }
                    }
                },
                position: {
                    viewport: $(window)
                },
                hide: {
                    fixed: true,
                    delay: 300
                },
                style: "qtip-wiki"
            })
        });
        function follow(o) {
            var data_str = o.parent().attr("data-param");
            eval("data_str = " + data_str);
            $.getJSON(SHOP_SITE_URL + "/index.php?act=member_snsfriend&op=addfollow&callback=?&mid=" + data_str.mid,
            function(t) {
                if (t) {
                    $('[nctype="follow-handle' + data_str.mid + '"]').html('<a href="javascript:void(0);" >已关注</a> <a href="javascript:void(0);" nctype="nofollow">取消关注</a>').find('a[nctype="nofollow"]').click(function() {
                        onfollow($(this))
                    })
                }
            })
        }
        function onfollow(o) {
            var data_str = o.parent().attr("data-param");
            eval("data_str = " + data_str);
            $.getJSON(SHOP_SITE_URL + "/index.php?act=member_snsfriend&op=delfollow&callback=?&mid=" + data_str.mid,
            function(t) {
                if (t) {
                    $('[nctype="follow-handle' + data_str.mid + '"]').html('<a href="javascript:void(0);" nctype="follow">加关注</a>').find('a[nctype="follow"]').click(function() {
                        follow($(this))
                    })
                }
            })
        }
        function shop_function(t) {
            return
        }
        function circle_function(t) {
            var a = $('<dd class="ajax-info"></dd>');
            $.each(t,
            function(t, e) {
                a.append('<div class="rank-div" title="' + e.circle_name + "圈等级" + e.cm_level + "，经验值" + e.cm_exp + '"><a href="' + CIRCLE_SITE_URL + "/index.php?act=group&c_id=" + e.circle_id + '" target="_blank">' + e.circle_name + '</a><i></i><em class="rank-em rank-' + e.cm_level + '">' + e.cm_level + "</em></div>")
            });
            return a
        }
        function microshop_function(t) {
            var a = $('<dd class="ajax-info"></dd>');
            a.append('<span class="ajax-info-microshop">随心看：' + t.goods_count + "</span>");
            a.append('<span class="ajax-info-microshop">个人秀：' + t.personal_count + "</span>");
            return a
        }
    }
})(jQuery); (function(t) {
    t.fn.nc_region = function(a) {
        var e = t(this);
        var n = t.extend({},
        {
            area_id: 0,
            region_span_class: "_region_value"
        },
        a);
        return this.each(function() {
            var a = t(this);
            if (a.val() === "") {
                o(a)
            } else {
                var e = t('<span class="' + n.region_span_class + '">' + a.val() + "</span>");
                var i = t('<input type="button" value="编辑" />');
                a.after(e);
                e.after(i);
                i.on("click",
                function() {
                    e.hide();
                    i.hide();
                    o(a)
                })
            }
        });
        function o(a) {
            n.$area = t("<select></select>");
            a.after(n.$area);
            l(function() {
                d(n.$area, n.area_id)
            })
        }
        function d(a, n) {
            if (a && nc_a[n].length > 0) {
                var o = [];
                o = nc_a[n];
                a.append("<option>-请选择-</option>");
                for (i = 0; i < o.length; i++) {
                    a.append("<option value='" + o[i][0] + "'>" + o[i][1] + "</option>")
                }
            }
            a.on("change",
            function() {
                t(this).nextAll("select").remove();
                var a = "";
                e.nextAll("select").each(function() {
                    a += t(this).find("option:selected").text() + " "
                });
                e.val(a);
                var i = t(this).val();
                if (i > 0) {
                    if (nc_a[i] && nc_a[i].length > 0) {
                        var n = t("<select></select>");
                        t(this).after(n);
                        d(n, i)
                    }
                }
            })
        }
        function l(a) {
            if (typeof nc_a === "undefined") {
                t.getJSON(SHOP_SITE_URL + "/index.php?act=index&op=json_area&callback=?",
                function(t) {
                    nc_a = t;
                    a()
                })
            } else {
                a()
            }
        }
    }
})(jQuery);
function addcart(goods_id, quantity, callbackfunc) {
    if (!quantity) return false;
    var url = "index.php?act=cart&op=add";
    quantity = parseInt(quantity);
    $.getJSON(url, {
        goods_id: goods_id,
        quantity: quantity
    },
    function(data) {
        if (data != null) {
            if (data.state) {
                if (callbackfunc) {
                    eval(callbackfunc + "(data)")
                }
                load_cart_information();
                $("#rtoolbar_cartlist").load("index.php?act=cart&op=ajax_load&type=html")
            } else {
                alert(data.msg)
            }
        }
    })
}
