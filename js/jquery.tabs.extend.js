/**
 * @author {CaoGuangHui}
 */
$.extend($.fn.tabs.methods, {
    /**
     * tabs组件每个tab panel对应的小工具条绑定的事件没有传递事件参数
     * 本函数修正这个问题
     * @param {[type]} jq [description]
     */
    addEventParam: function(jq) {
        return jq.each(function() {
            var that = this;
            var headers = $(this).find('>div.tabs-header>div.tabs-wrap>ul.tabs>li');
            headers.each(function(i) {
                var tools = $(that).tabs('getTab', i).panel('options').tools;
                if (typeof tools != "string") {
                    $(this).find('>span.tabs-p-tool a').each(function(j) {
                        $(this).unbind('click').bind("click", {
                            handler: tools[j].handler
                        }, function(e) {
                            if ($(this).parents("li").hasClass("tabs-disabled")) {
                                return;
                            }
                            e.data.handler.call(this, e);
                        });
                    });
                }
            })
        });
    }
});