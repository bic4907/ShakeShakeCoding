<template>
    <ul class="block-inventory list-unstyled">
        <draggable :list="blocks" @end="onDragDropEnd">
            <transition-group>
                <li v-for="block in blocks" :key="block.uuid">
                    <InActiveBlockComponent
                        v-bind:block="block"
                    ></InActiveBlockComponent>
                </li>
            </transition-group>
        </draggable>
    </ul>
</template>

<script>
    import InActiveBlockComponent from "./InActiveBlockComponent";

    export default {
        name: "BlockInventoryComponent",
        components: {InActiveBlockComponent},
        props: ['blocks'],
        methods: {
            onDragDropEnd: function (evt) {

                if (
                    evt.oldIndex == evt.newIndex &&
                    evt.originalEvent.clientX < document.body.clientWidth / 2) {

                    var item = this.blocks.splice(evt.newIndex, 1)[0];
                    item.depth = 0;
                    item.lineNumber = null;

                    this.$emit('moveItemToDisp', item);
                    return;
                }

            }
        }
    }
</script>

<style scoped>

</style>
