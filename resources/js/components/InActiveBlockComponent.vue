<template>
    <div class="block-item row d-inline">
        <div class="col d-inline block-inline" v-html="renderdContent"></div>
        <div :class="{'d-none':!block.forFlag}" v-b-tooltip.hover :title="block.forMsg">
            <i class="fas fa-question-circle-o" ></i>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InActiveBlockComponent",
        props: ['block'],

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

        }
    }
</script>

<style scoped>

</style>
