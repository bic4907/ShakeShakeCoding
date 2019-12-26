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
        data: function() {
            return {
                message: 'hr457d67',
                inactiveBlock: [
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'for i in range([[input:abc]], [[input:zxc]]):', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},

                ],
                activeBlock: [
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'for i in range([[input:abc]], 1):', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'for i in range([[input:abc]], [[input:zxc]]):', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = 1', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},
                    {'uuid':uuid.v1(), 'type': 'user', 'content':'a = [[input:inchang]]', 'depth':0},

                ]
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
