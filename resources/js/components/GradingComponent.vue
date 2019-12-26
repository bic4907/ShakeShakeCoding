<template>
    <div class="grading-component">
        <div class="controller row no-gutters">
            <div class="col-auto">
                <div class="d-inline" v-on:click="runGrade" v-if="currentMessage == '대기 중'">
                    <i class="fas fa-play-circle ml-3 mt-2" style="color:green;font-size:2.2em;cursor:pointer;"></i>
                </div>
                <div class="d-inline" v-if="currentMessage != '대기 중'">
                    <i class="fas fa-stop-circle ml-3 mt-2" style="color:darkred;font-size:2.2em;cursor:pointer;"></i>
                </div>
            </div>
            <div class="col-auto">
                <span class="ml-3">{{ currentMessage }}</span>
            </div>
        </div>
        <div class="resultDisplay">
            {{ currentResponse }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "GradingComponent",
        props: ['blocks'],
        data: function() {
            return {
                currentMessage: '대기 중',
                currentResponse: '...'
            }
        },
        methods: {
            runGrade: function() {
                var self = this;
                console.log('채점 시작');
                this.currentMessage = '채점 중';

                console.log(this.blocks);

                axios.post('/solve/1', {
                        blocks: this.blocks
                    })
                    .then(function (response) {
                        self.currentResponse = response.data;
                        console.log(response);

                        self.currentMessage = '대기 중';
                        console.log('채점 완료');
                    })
                    .catch(function (error) {
                        console.log('오류가 나면서 완료됨');
                        console.log(error);
                    })
                    .finally(function () {
                        self.currentMessage = '대기 중';
                    });

            }
        }
    }
</script>

<style scoped>

</style>
