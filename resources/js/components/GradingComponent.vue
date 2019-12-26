<template>
    <div class="grading-component">
        <div class="controller row no-gutters">

            <div class="d-inline" v-on:click="runGrade" v-if="currentMessage == '대기 중'">
                <i class="fas fa-play-circle ml-3 mt-2" style="color:green;font-size:2.2em;cursor:pointer;"></i>
            </div>

            <div class="col-auto">
                <div class="d-inline" v-if="currentMessage != '대기 중'">
                    <i class="fas fa-stop-circle ml-3 mt-2" style="color:darkred;font-size:2.2em;cursor:pointer;"></i>
                </div>
            </div>
            <div class="col-auto">
                <span class="ml-3">{{ currentMessage }}</span>
            </div>
        </div>
        <div class="resultDisplay">
            <div v-for="line in currentResponse">
                <div class="row no-gutters">
                    <div class="col ml-2">{{ line }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "GradingComponent",
        props: ['blocks', 'question'],
        data: function() {
            return {
                currentMessage: '대기 중',
                currentResponse: ''
            }
        },
        methods: {
            runGrade: function() {
                var self = this;


                var convertedBlocks = this.gatherInputs(self.blocks);

                console.log('채점 시작');
                this.currentMessage = '채점 중';

                var gradingUrl = self.question.gradingUrl;
                self.currentResponse = [];

                axios.post(gradingUrl, {
                        blocks: convertedBlocks
                    })
                    .then(function (response) {
                        self.currentResponse = response.data;
                        console.log(response);

                        self.currentMessage = '대기 중';
                        console.log('채점 완료');
                    })
                    .catch(function (error) {
                        console.log('오류가 나면서 완료됨');
                        self.currentResponse = [error.toString()];
                        console.log(error);
                    })
                    .finally(function () {
                        self.currentMessage = '대기 중';
                    });

            },
            gatherInputs: function(blocks) {

                var newBlocks = [];
                const regexp = '\[\[input:.[a-zA-Z]+\]\]';


                $.each(blocks, function(i, block) {

                    var copyBlock = {};

                    copyBlock.content = block.content;
                    copyBlock.type = block.type;
                    copyBlock.depth = block.depth;

                    while (copyBlock.content.match(regexp) != null) {
                        var found = copyBlock.content.match(regexp);
                        var target = found[0];
                        var uid = target.substr(8, target.length - 10);

                        var origin = '[[input:' + uid + ']]';
                        var dest = $('input[data-uid='+uid+']').val() ? $('input[data-uid='+uid+']').val() : '';

                        copyBlock.content = copyBlock.content.replace(origin, dest);

                    }
                    newBlocks.push(copyBlock);
                })

                return newBlocks;
            }
        }
    }
</script>

<style scoped>

</style>
