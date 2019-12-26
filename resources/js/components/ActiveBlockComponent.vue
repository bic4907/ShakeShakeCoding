<template>
    <div class="block-item row d-inline" v-bind:style="renderdStyleFirst">
        <div class="col d-inline" v-if="block.type == 'user'" v-html="">{{ block.lineNumber }}</div>
        <div class="col d-inline block-inline" v-html="renderdContent" v-bind:style="renderdStyleSecond" :class="{'warning':block.warnFlag}"></div>
        <div :class="{'d-none':!block.forFlag}" v-b-tooltip.hover :title="block.forMsg">
            <i class="fas fa-question-circle-o" ></i>
        </div>
        <div :class="{'d-none':!block.warnFlag}" v-b-tooltip.hover :title="block.warnMsg" style="cursor:pointer">
            <i class="fas fa-exclamation-triangle" ></i>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ActiveBlockComponent",
        props: ['block'],
        mounted: function() {
            var self = this;
            // setInterval(function() {
            //     self.$forceUpdate();
            //
            // }, 500)
        },
        computed: {
            renderdContent: function() {
                var html = this.block.content

                const regexp = '\[\[input:.[a-zA-Z]+\]\]'

                while (html.match(regexp) != null) {
                    var found = html.match(regexp)
                    var target = found[0]
                    var uid = target.substr(8, target.length - 10)
                    var chgHtml = '<input type="text" data-block-type="input" data-uid="' + uid + '" maxlength="10">'
                    html = html.replace(target, chgHtml)
                }

                return html
            },
            renderdStyleFirst: function() {
                var height = null;
                var padding = null;
                var opacity = null;
                var fontsize = null;
                var margin = null;
                if(this.block.type == 'end-for' || this.block.type =='begin-for') {
                    height = 0;
                    fontsize = '10px';
                    padding = '1em';
                    margin = 0;
                    opacity = 0.3;
                }
                return {
                    padding: padding,
                    height: height,
                    opacity: opacity,
                    fontSize: fontsize,
                    margin: margin,
                }
            },
            renderdStyleSecond: function() {

                var height = null;
                var padding = null;
                var opacity = null;
                var fontsize = null;
                var margin = null;

                if(this.block.type == 'end-for' || this.block.type =='begin-for') {
                    height = 0;
                    fontsize = '10px';
                    padding = '1em';
                    margin = 0;
                    opacity = 0.3;
                }
                return {
                    marginLeft: this.block.depth * 80 + 'px'
                }
            },
            invalidate: function() {
                console.log('invalidate');
            }
        }
    }
</script>

<style scoped>

</style>
