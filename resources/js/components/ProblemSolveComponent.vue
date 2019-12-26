<template>
    <div class="problem-solve">
        <div class="resizable-section row no-gutters">
            <div class="col">
                <BlockDisplayComponent
                    v-bind:blocks="activeBlock"
                    v-on:moveItemToInv="moveItemToInv"
                ></BlockDisplayComponent>
            </div>
            <div class="col-4 inven-component">
                <BlockInventoryComponent
                    v-bind:blocks="inactiveBlock"
                    v-on:moveItemToDisp="moveItemToDisp"
                ></BlockInventoryComponent>
            </div>
        </div>
        <div>
            <GradingComponent
                v-bind:blocks="activeBlock"
                v-bind:question="question"
                v-on:debugReceived="debugReceived"
            ></GradingComponent>
        </div>
    </div>

</template>

<script>
    import BlockDisplayComponent from "./BlockDisplayComponent";
    import BlockInventoryComponent from "./BlockInventoryComponent";
    import draggable from 'vuedraggable';
    import { uuid } from 'vue-uuid';
    import GradingComponent from "./GradingComponent";

    export default {
        name: "ProblemSolveComponent",
        components: {GradingComponent, BlockInventoryComponent, BlockDisplayComponent, draggable},
        props: ['question', 'submission'],
        mounted: function() {
            this.getBlocks()
        },
        data: function() {
            return {
                inactiveBlock: [],
                activeBlock: []
            }
        },
        methods: {
            moveItemToInv: function(item) {
                this.inactiveBlock.push(item)
            },
            moveItemToDisp: function(item) {
                if(item.content.startsWith('for ')) {
                    this.activeBlock.push(
                        {'uuid': uuid.v1(), 'type': 'begin-for', 'content':'begin-for', 'depth':0}
                    )
                }
                this.activeBlock.push(item)
                if(item.content.startsWith('for ')) {
                    this.activeBlock.push(
                        {'uuid': uuid.v1(), 'type': 'end-for', 'content':'end-for', 'depth':1}
                    )
                }
            },
            getBlocks: function() {
                var self = this;

                var blockUrl = self.question.blockUrl;

                axios.get(blockUrl)
                    .then(function (response) {

                        var tmpInactive = [];
                        var tmpActive = [];

                        var myBlocks = response.data;

                        for(var i = 0; i < myBlocks.length; i++) {
                            myBlocks[i]['uuid'] = uuid.v1();
                            myBlocks[i]['depth'] = 0;
                            myBlocks[i]['type'] = 'user';
                            myBlocks[i]['lineNumber'] = 0;

                            if(myBlocks[i]['block_type'] == 0) {
                                tmpInactive.push(myBlocks[i])
                            } else {
                                tmpActive.push(myBlocks[i])
                            }
                        }

                        self.activeBlock = tmpActive;
                        self.inactiveBlock = tmpInactive;

                        self.addSubBlocks();
                        self.tooltip();
                        self.$forceUpdate();
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(function () {
                    });

                // self.$refs.blockDispComp.renderDepth()
                // self.$refs.blockDispComp.markNumber()
            },
            getChild(name) {
                for(let child of this.$children) if (child.$options.name==name) return child;
            },
            debugReceived: function(debugInfo) {
                $.each(this.activeBlock, function(i, e) {
                    if(e.uuid != debugInfo['line']) {
                        e.warnFlag = false;
                        e.warnMsg = null;
                    } else {
                        e.warnFlag = true;
                        e.warnMsg = debugInfo['message'];
                    }
                })

            },
            tooltip: function() {
                $.each(this.activeBlock, function(i, e) {
                    if(e.content.startsWith('for ')) {
                        e.forFlag = true;
                        e.forMsg = "for i in range(a,b): (a부터 b까지 i에 대입하며 반복)";
                    } else {
                        e.forFlag = false;
                        e.forMsg = null;
                    }
                })
            },
            addSubBlocks: function() {
                console.log(this.activeBlock);
                for(var i = 0; i < this.activeBlock.length; i++) {

                    if(this.activeBlock[i].content.startsWith('for ')) {
                        this.activeBlock.splice(i, 0,
                            {'uuid': uuid.v1(), 'type': 'begin-for', 'content':'begin-for', 'depth':this.activeBlock[i].depth}
                        );
                        i++;
                    }

                    if(
                        i + 1 <= this.activeBlock.length &&
                        this.activeBlock[i].content.startsWith('for ') &&
                        this.activeBlock[i].content.depth == this.activeBlock[i + 1].content.depth
                    ) {

                        this.activeBlock.splice(i + 1, 0,
                            {'uuid': uuid.v1(), 'type': 'end-for', 'content':'end-for', 'depth':this.activeBlock[i].depth}
                        );
                        i++;

                    }

                }
            },
        }
    }

    function resizeSolverComponent() {
        var $section = $('.resizable-section');
        var screenH = window.innerHeight;

        $section.height(screenH - 232)
    }
    $(document).ready(resizeSolverComponent)
    $(window).resize(resizeSolverComponent)

    $(document).ready(function() {
        $('#exampleModalLong').modal('show')
    })
</script>

<style scoped>

</style>
