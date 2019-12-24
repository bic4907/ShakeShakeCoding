<template>
    <div class="container-fluid problem-solve">
        <div class="row">
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
    </div>
</template>

<script>
    import BlockDisplayComponent from "./BlockDisplayComponent";
    import BlockInventoryComponent from "./BlockInventoryComponent";
    import draggable from 'vuedraggable';
    import { uuid } from 'vue-uuid';

    export default {
        name: "ProblemSolveComponent",
        components: {BlockInventoryComponent, BlockDisplayComponent, draggable},
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
</script>

<style scoped>

</style>
