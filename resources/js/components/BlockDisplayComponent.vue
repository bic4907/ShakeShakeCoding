<template>
    <ul class="block-display list-unstyled">
        <draggable :list="blocks" :move="checkMovable" @end="onDragDropEnd">
            <transition-group>
                <li v-for="block in blocks" :key="block.uuid">
                    <ActiveBlockComponent
                        :key="block.uuid"
                        v-bind:block="block"
                    ></ActiveBlockComponent>
                </li>
            </transition-group>

        </draggable>
    </ul>
</template>

<script>
    import ActiveBlockComponent from "./ActiveBlockComponent";
    import { uuid } from 'vue-uuid';

    export default {
        name: "BlockDisplayComponent",
        components: {ActiveBlockComponent},
        props: ['blocks'],
        mounted: function() {
            this.addSubBlocks()
            this.renderDepth()
            this.markNumber()
        },
        methods: {
            checkMovable: function(evt) {
                // 옮길수 없는 블럭을 예외처리
                if(evt.draggedContext.element.type == 'end-for' ||
                    evt.draggedContext.element.type == 'begin-for'
                ) {
                    return false;
                }
                return true;
            },
            addSubBlocks: function() {

                for(var i = 0; i < this.blocks.length; i++) {

                    if(this.blocks[i].content.startsWith('for ')) {
                        this.blocks.splice(i, 0,
                            {'uuid': uuid.v1(), 'type': 'begin-for', 'content':'begin-for', 'depth':this.blocks[i].depth}
                        );
                        i++;
                    }

                    if(
                        i + 1 <= this.blocks.length &&
                        this.blocks[i].content.startsWith('for ') &&
                        this.blocks[i].content.depth == this.blocks[i + 1].content.depth
                    ) {

                        this.blocks.splice(i + 1, 0,
                            {'uuid': uuid.v1(), 'type': 'end-for', 'content':'end-for', 'depth':this.blocks[i].depth}
                        );
                        i++;

                    }

                }
            },
            onDragDropEnd: function(evt) {

                if(
                    evt.oldIndex == evt.newIndex &&
                    this.blocks[evt.newIndex].type == 'user' &&
                    evt.originalEvent.clientX > document.body.clientWidth / 2) {

                    var item = this.blocks.splice(evt.newIndex, 1)[0];

                    // Remove Begin-for
                    this.blocks.splice(evt.newIndex - 1, 1);
                    var iterIndex = evt.newIndex - 1;
                    for(var iterIndex =  evt.newIndex - 1; iterIndex < this.blocks.length; iterIndex++) {
                        if(
                            iterIndex >= 0 &&
                            this.blocks[iterIndex].type == 'end-for' && this.blocks[iterIndex].depth == item.depth + 1) {
                            this.blocks.splice(iterIndex, 1);
                            break;
                        }
                    }

                    item.depth = 0;
                    item.lineNumber = null;

                    this.$emit('moveItemToInv', item);

                    return;
                }


                if(this.blocks[evt.newIndex].content.startsWith('for ')) {

                    if(evt.newIndex > 0 &&
                        this.blocks[evt.newIndex - 1].type == 'begin-for') {

                        arrayMove(this.blocks, evt.newIndex, evt.newIndex - 1)
                        evt.newIndex--;
                    }

                    var itemsToMove = [];

                    var beginIndex;
                    var iterIndex;
                    var originDepth;

                    if (evt.oldIndex > evt.newIndex) {
                        beginIndex = evt.oldIndex;
                        itemsToMove.push(this.blocks.splice(beginIndex, 1)[0])

                        originDepth = itemsToMove[0].depth + 1;

                        iterIndex = evt.oldIndex;
                        while(
                            iterIndex < this.blocks.length &&
                            this.blocks[iterIndex].depth >= originDepth) {

                            itemsToMove.push(this.blocks.splice(iterIndex, 1)[0])
                        }

                        /**
                         * itemToMove에 담긴 아이템들을 다시 blocks 변수로 렌더링함
                         */
                        while(itemsToMove.length > 1) {
                            this.blocks.splice(evt.newIndex + 1, 0,
                                itemsToMove.pop()
                            )
                        }
                        this.blocks.splice(evt.newIndex, 0, itemsToMove.pop())

                    } else if (evt.oldIndex < evt.newIndex) {

                        beginIndex = evt.oldIndex - 1;
                        itemsToMove.push(this.blocks.splice(beginIndex, 1)[0]);

                        originDepth = itemsToMove[0].depth + 1;

                        iterIndex = evt.oldIndex - 1;

                        while(
                            iterIndex < this.blocks.length &&
                            this.blocks[iterIndex].depth >= originDepth) {

                            itemsToMove.push(this.blocks.splice(iterIndex, 1)[0])
                        }

                        /**
                         * itemToMove에 담긴 아이템들을 다시 blocks 변수로 렌더링함
                         */
                        var collectedItems = itemsToMove.length
                        while(itemsToMove.length > 1) {
                            this.blocks.splice(evt.newIndex - collectedItems + 1, 0,
                                itemsToMove.pop()
                            )
                        }


                        this.blocks.splice(evt.newIndex - collectedItems, 0, itemsToMove.pop())

                    }


                } else if(
                    !this.blocks[evt.newIndex].content.startsWith('for ') &&
                    evt.newIndex > 0 &&
                    this.blocks[evt.newIndex - 1].type == 'begin-for') {
                    arrayMove(this.blocks, evt.newIndex , evt.newIndex - 1)
                }

                this.renderDepth()
                this.markNumber()
                this.$forceUpdate()

            },
            renderDepth: function() {
                var depth = 0;
                for(var i = 0; i < this.blocks.length; i++) {

                    var flag = false;

                    this.blocks[i].depth = depth;
                    if (this.blocks[i].content.startsWith('for ')) {
                        depth++;
                        flag = true;
                    }
                    if(this.blocks[i].type == 'end-for') {
                        depth--;
                        flag = true;
                    }
                }
            },
            markNumber: function() {
                var lineNumber = 1;
                for(var i = 0; i < this.blocks.length; i++) {
                    if(this.blocks[i].type == 'user') {
                        this.blocks[i].lineNumber = lineNumber
                        lineNumber++;
                    }
                }

            },
        }
    }
</script>

<style scoped>

</style>
