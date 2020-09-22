<template>


	<!--questionBox-->
	<div class="questionBox" id="app">

		<!-- transition -->
		<transition :duration="{ enter: 500, leave: 300 }" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut" mode="out-in">

			<!--qusetionContainer-->
			<div class="questionContainer" v-if="questionIndex<quiz.questions.length" v-bind:key="questionIndex">

				<header>
					<h1 class="title is-6">VueQuiz</h1>
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
					<div class="option" v-for="(response, index) in quiz.questions[questionIndex].responses" @click="selectOption(index)" :class="{ 'is-selected': userResponses[questionIndex] == index}" :key="index">
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
                    {{ (userResponses[questionIndex]==null)?'Skip':'Next' }}
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
					You did {{ (score()>7?'an amazing':(score()<4?'a poor':'a good')) }} job!
				</h2>
				<p class="subtitle">
					Total score: {{ score() }} / {{ quiz.questions.length }}
				</p>
					<br>
					<a class="button" @click="restart()">restart <i class="fa fa-refresh"></i></a>
				<!--/resultTitleBlock-->

			</div>
			<!--/quizCompetedResult-->

		</transition>

	</div>
	<!--/questionBox-->

</template>

<script>
import './lesson.css'

// const Vue = window.vue;

var quiz = {
      user: "Dave",
      questions: [
         {
            text: "What is the full form of HTTP?",
            responses: [
               { text: "Hyper text transfer package" },
               { text: "Hyper text transfer protocol", correct: true },
               { text: "Hyphenation text test program" },
               { text: "None of the above" }
            ]
         },
         {
            text: "HTML document start and end with which tag pairs?",
            responses: [
               { text: "HTML", correct: true },
               { text: "WEB" },
               { text: "HEAD" },
               { text: "BODY" }
            ]
         },
         {
            text: "Which tag is used to create body text in HTML?",
            responses: [
               { text: "HEAD" },
               { text: "BODY", correct: true },
               { text: "TITLE" },
               { text: "TEXT" }
            ]
         },
         {
            text: "Outlook Express is _________",
            responses: [
               { text: "E-Mail Client", correct: true },
               { text: "Browser" },
               {
                  text: "Search Engine"
               },
               { text: "None of the above" }
            ]
         },
         {
            text: "What is a search engine?",
            responses: [
               { text: "A hardware component " },
               {
                  text: "A machinery engine that search data"
               },
               { text: "A web site that searches anything", correct: true },
               { text: "A program that searches engines" }
            ]
         },
         {
            text:
               "What does the .com domain represents?",
            responses: [
               { text: "Network" },
               { text: "Education" },
               { text: "Commercial", correct: true },
               { text: "None of the above" }
            ]
         },
         {
            text: "In Satellite based communication, VSAT stands for? ",
            responses: [
               { text: " Very Small Aperture Terminal", correct: true },
               { text: "Varying Size Aperture Terminal " },
               {
                  text: "Very Small Analog Terminal"
               },
               { text: "None of the above" }
            ]
         },
         {
            text: "What is the full form of TCP/IP? ",
            responses: [
               { text: "Telephone call protocol / international protocol" },
               { text: "Transmission control protocol / internet protocol", correct: true },
               { text: "Transport control protocol / internet protocol " },
               { text: "None of the above" }
            ]
         },
         {
            text:
               "What is the full form of HTML?",
            responses: [
               {
                  text: "Hyper text marking language"
               },
               { text: "Hyphenation text markup language " },
               { text: "Hyper text markup language", correct: true },
               { text: "Hyphenation test marking language" }
            ]
         },
         {
            text: "\"Yahoo\", \"Infoseek\" and \"Lycos\" are _________?",
            responses: [
               { text: "Browsers " },
               { text: "Search Engines", correct: true },
               { text: "News Group" },
               { text: "None of the above" }
            ]
         }
      ]
   },
   userResponseSkelaton = Array(quiz.questions.length).fill(null);


  export default {
 
  data() {
    return {
      quiz: quiz,
      questionIndex: 0,
      userResponses: userResponseSkelaton,
      isActive: false
    }
   },
   filters: {
      charIndex: function(i) {
         return String.fromCharCode(97 + i);
      }
   },
   methods: {
		 restart: function(){
			 	this.questionIndex=0;
		 		this.userResponses=Array(this.quiz.questions.length).fill(null);
		 },
      selectOption: function(index) {
         // console.log(this)
         this.userResponses[this.questionIndex] = index
         // this.$root.set(this.userResponses, this.questionIndex, index);
         console.log(this.userResponses);
      },
      next: function() {
         if (this.questionIndex < this.quiz.questions.length)
            this.questionIndex++;
      },

      prev: function() {
         if (this.quiz.questions.length > 0) this.questionIndex--;
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
      }
   }
   }


</script>
