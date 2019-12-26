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
                        var myBlocks = response.data;

                        for(var i = 0; i < myBlocks.length; i++) {
                            myBlocks[i]['uuid'] = uuid.v1();
                            myBlocks[i]['depth'] = 0;
                            myBlocks[i]['type'] = 'user';
                            myBlocks[i]['lineNumber'] = 0;

                            if(myBlocks[i]['block_type'] == 0) {
                                self.inactiveBlock.push(myBlocks[i])
                            } else {
                                self.activeBlock.push(myBlocks[i])
                            }

                        }

                        self.$forceUpdate();
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .finally(function () {
                    });
            },
            debugReceived: function(debugInfo) {
                console.log(debugInfo)

                $.each(this.activeBlock, function(i, e) {
                    if(e.uuid != debugInfo['line']) {
                        e.warnFlag = false;
                        e.warnMsg = null;
                    } else {
                        console.log('found!', i);
                        e.warnFlag = true;
                        e.warnMsg = debugInfo['message'];
                    }
                })

            }
        }
    }

    function resizeSolverComponent() {
        var $section = $('.resizable-section');
        var screenH = window.innerHeight;

        $section.height(screenH - 232)
    }
    $(document).ready(resizeSolverComponent)
    $(window).resize(resizeSolverComponent)
</script>

<style scoped>

</style>
