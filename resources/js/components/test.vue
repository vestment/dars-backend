<template>

<div>	<!--questionBox-->
 <div>
    <flip-countdown :deadline="testTimer"></flip-countdown>
  </div>

  <div class="questionBox">

    
		<!-- transition -->
		<transition :duration="{ enter: 500, leave: 300 }" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut" mode="out-in">

			<!--qusetionContainer-->
			<div class="questionContainer" v-if="questionIndex<quiz.questions.length" v-bind:key="questionIndex">

				<header>
					<h1 class="title is-6">{{this.testData.title}}</h1>
					<!--progress-->
					<div class="progressContainer">
						<progress class="progress is-info is-small" :value="(questionIndex/quiz.questions.length)*100" max="100">{{(questionIndex/quiz.questions.length)*100}}%</progress>
						<p>{{(questionIndex/quiz.questions.length)*100}}% complete</p>
					</div>
					<!--/progress-->
				</header>

				<!-- questionTitle -->
				<h2 class="titleContainer title">{{ quiz.questions[questionIndex].text }}</h2>

				<!-- quizOptions -->
				<div class="optionContainer">
					<div class="option" v-for="(response, index) in quiz.questions[questionIndex].responses" @click="selectOption(index,response.id)" :class="{ 'is-selected': userResponses[questionIndex] == response.id}"  :key="response.id">
						{{ index | charIndex }}. {{ response.text }}
					</div>
				</div>

				<!--quizFooter: navigation and progress-->
				<footer class="questionFooter">

					<!--pagination-->
					<nav class="pagination" role="navigation" aria-label="pagination">

						<!-- back button -->
						<a class="button" v-on:click="prev();" :disabled="questionIndex < 1">
                    Back
                  </a>

						<!-- next button -->
						<a class="button" :class="(userResponses[questionIndex]==null)?'':'is-active'" v-on:click="next();" :disabled="questionIndex>=quiz.questions.length">
                    {{ (userResponses[questionIndex]==null)?'skip':'Next' }}
                  </a>

					</nav>
					<!--/pagination-->

				</footer>
				<!--/quizFooter-->

			</div>
			<!--/questionContainer-->

			<!--quizCompletedResult-->
			<div v-if="questionIndex >= quiz.questions.length" v-bind:key="questionIndex" class="quizCompleted has-text-centered">

				<!-- quizCompletedIcon: Achievement Icon -->
				<span class="icon">
                <i class="fa" :class="score()>3?'fa-check-circle-o is-active':'fa-times-circle'"></i>
              </span>

				<!--resultTitleBlock-->
				<h2 class="title">
					You did {{ (score()>7?'an amazing':(score()<=1?'a poor':'a good')) }} job!
				</h2>
				<p class="subtitle">
					Total score: {{this.testScore}} / {{ quiz.questions.length }}
				</p>
					<br>
					<a class="button" @click="restart()">restart <i class="fa fa-refresh"></i></a>
				<!--/resultTitleBlock-->

			</div>
			<!--/quizCompetedResult-->

		</transition>

	</div>
	<!--/questionBox-->
</div>

</template>

<script>
import './lesson.css'
  import FlipCountdown from 'vue2-flip-countdown'

// const token = localStorage.getItem('token') ? localStorage.getItem('token') : '';

// const Vue = window.vue;
import axios from "../axios";
var quiz = {
      user: "Dave",
      questions: []
   },

   userResponseSkelaton = Array(quiz.questions.length).fill(null);


  export default {



  data() {
    return {
       showDays : false,
     testTimer:0,
      testData: [],
      quiz: quiz,
      questionIndex: 0,
      userResponses: userResponseSkelaton,
      isActive: false,
      test_id:'',
     testScore:0,
      question_data:[],
      slug: this.$route.params.slug ? this.$route.params.slug : this.slug,
      testDate:'',
      finalFormat:'',
    
    }
   },
   filters: {
      charIndex: function(i) {
         return String.fromCharCode(97 + i);
      }
  },
  watch: {
    $route() {
      this.slug = this.$route.params.slug
    },
    slug() {
      this.getData(this.slug)
    }
  },
  created() {
 
    
    console.log(this.slug)
    this.getData(this.slug)
   },
   components: { FlipCountdown },
   methods: {
     
  
		 restart: function(){
			 	this.questionIndex=0;
		 		this.userResponses=Array(quiz.questions.length).fill(null);
		 },
      selectOption: function(index,id) {

         // this.userResponses[this.questionIndex] === index
         this.userResponses[this.questionIndex] = id
        console.log(this.questionIndex)
         // this.$root.set(this.userResponses, this.questionIndex, index);
         this.$forceUpdate();
      },




      prev: function() {
         if (quiz.questions.length > 0) this.questionIndex--;
      },
      // Return "true" count in userResponses
      score: function() {
         var score = 0;
         for (let i = 0; i < this.userResponses.length; i++) {
            if (
               typeof this.quiz.questions[i].responses[
                  this.userResponses[i]
               ] !== "undefined" &&
               this.quiz.questions[i].responses[this.userResponses[i]].correct
            ) {
               score = score + 1;
            }
         }
         return score;

         //return this.userResponses.filter(function(val) { return val }).length;
    },
//       sec2time(timeInSeconds) {
       
//     var pad = function(num, size) { return ('000' + num).slice(size * -1); },
//     time = parseFloat(timeInSeconds).toFixed(3),
//     hours = Math.floor(time / 60 / 60),
//     minutes = Math.floor(time / 60) % 60,
//     seconds = Math.floor(time - minutes * 60),
//     milliseconds = time.slice(-3);
//     this.finalFormat = pad(hours, 2) + ':' + pad(minutes, 2) + ':' + pad(seconds, 2) + ',' + pad(milliseconds, 3);

//     return this.finalFormat;
// },
    getData(slug) {
      axios.post('/api/v1/single-test', {test: slug})
          .then(res => {
            this.testData = res.data.response.test
            this.testDate = new Date().toJSON().slice(0,10);
            this.testTimer = this.testData.timer

            for(var i=0; i<=this.testData.questions.length-1; i++ )
            {
               let obj = {
            text: this.testData.questions[i].question,
            responses: []
         };
             var  text = this.testData.questions[i].question

            //  quiz.questions[i].text = this.testData.questions[i].question

               for(var j=0; j<=this.testData.questions[i].options.length-1; j++)
               {
                 var responses =
               {
                  text: this.testData.questions[i].options[j].option_text ,
               correct:this.testData.questions[i].options[j].correct,
               id:this.testData.questions[i].options[j].id
                };
               obj.responses.push(responses)
               }
               quiz.questions.push(obj);
            }
              //   this.playerOptions.sources[0].src = this.courseData.lesson.media_video.url

              //   $('.course-title-header').text(this.courseData.course.title)
              //   $('.close-lesson').attr('href', this.courseData.course_page)
              //   $('.course-progress').text(this.courseData.course_progress + ' %')
              //   $('.progress-bar').css('width', this.courseData.course_progress + '%')

          }).catch(err => {
        console.log(err)
      })
    },
   
        next: function() {
         if (this.questionIndex < quiz.questions.length-1)
         {

            this.questionIndex++;
            console.log(this.questionIndex,quiz.questions.length)
         }else
         {
           console.log(this.testData.questions[1].id)
            for(var i=0; i<this.testData.questions.length;i++){
               let questionObject = {
                  question_id:this.testData.questions[i].id,
                  ans_id: this.userResponses[i]

               }
            this.question_data.push(questionObject)
            }

              axios.post('/api/v1/save-test',
                {
                   test_id : this.testData.id,
                   question_data : this.question_data

                 }
                 )
          .then(res => {
              this.questionIndex++;
              this.testScore = res.data.score



         })
         }
      },
  }
}


</script>

