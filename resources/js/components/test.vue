<template>

  <div>  <!--questionBox-->
    <div>
      <flip-countdown @end="testEnded()" v-if="!testComplelete && attempts <= 2 "
                      v-bind:deadline="this.testData.timer.date"></flip-countdown>
    </div>

    <div class="questionBox w-100">

      <!-- transition -->
      <transition :duration="{ enter: 500, leave: 300 }" enter-active-class="animated zoomIn"
                  leave-active-class="animated zoomOut" mode="out-in">
        <div v-if="(quiz.questions.length === 0)" v-bind:key="questionIndex"
             class="quizCompleted quizHasNoQuestions has-text-centered">

          <!-- quizCompletedIcon: Achievement Icon -->
          <span class="icon">
                <i class="fa fa-times-circle"></i>
              </span>

          <!--resultTitleBlock-->
          <h2 class="title">
          Something went wrong
          </h2>
          <p class="subtitle">
            There are no questions found in this test
          </p>
          <!--/resultTitleBlock-->

        </div>
        <!--qusetionContainer-->
        <div class="questionContainer" v-if="questionIndex<quiz.questions.length && attempts <= 2"
             v-bind:key="questionIndex">

          <header>
            <h1 class="title is-6">{{ this.testData.title }}</h1>
            <!--progress-->
            <div class="progressContainer">
              <progress class="progress is-info is-small" :value="(questionIndex/quiz.questions.length)*100" max="100">
                {{ (questionIndex / quiz.questions.length) * 100 }}%
              </progress>
              <p>{{ (questionIndex / quiz.questions.length) * 100 }}% complete</p>
            </div>
            <!--/progress-->
          </header>

          <!-- questionTitle -->
          <h2 class="titleContainer title">{{ quiz.questions[questionIndex].text }}</h2>

          <!-- quizOptions -->
          <div class="optionContainer">
            <div class="option" v-for="(response, index) in quiz.questions[questionIndex].responses"
                 @click="selectOption(index,response.id)"
                 :class="{ 'is-selected': userResponses[questionIndex] == response.id}" :key="response.id">
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
              <a class="button" :class="(userResponses[questionIndex]==null)?'':'is-active'" v-on:click="next();"
                 :disabled="questionIndex>=quiz.questions.length">
                {{ (userResponses[questionIndex] == null) ? 'Next' : 'Next' }}
              </a>

            </nav>
            <!--/pagination-->

          </footer>
          <!--/quizFooter-->

        </div>
        <!--/questionContainer-->

        <!--quizCompletedResult-->
        <div v-if="(quiz.questions.length > 0 && questionIndex>=quiz.questions.length) || attempts >= 3" v-bind:key="questionIndex"
             class="quizCompleted has-text-centered">

          <!-- quizCompletedIcon: Achievement Icon -->
          <span class="icon">
                <i class="fa"
                   :class="resultData.test_result >= testData.min_grade ? 'fa-check-circle is-active':'fa-times-circle'"></i>
              </span>

          <!--resultTitleBlock-->
          <h2 class="title">
            You did {{
              (resultData.test_result >= testData.min_grade ? 'an amazing' : (resultData.test_result < testData.min_grade ? 'a poor' : 'a good'))
            }} job!
          </h2>
          <p class="subtitle">
            Total score: {{ resultData.test_result }} / {{ testData.totalScore }}
          </p>
          <br>
          <a class="button" v-if="attempts < 3" @click="restart()">Retest <i class="fa fa-refresh"></i></a>
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
import FlipCountdown from './countdown'

// const token = localStorage.getItem('token') ? localStorage.getItem('token') : '';

// const Vue = window.vue;
import axios from "../axios";

export default {


  data() {
    return {
      showDays: false,
      testTimer: "",
      testData: [],
      courseData: [],
      quiz:  {questions: []},
      attempts: 0,
      testComplelete: false,
      questionIndex: 0,
      userResponses: [],
      isActive: false,
      test_id: '',
      resultData: {test_result: 0},
      question_data: [],
      slug: this.$route.params.slug ? this.$route.params.slug : this.slug,
      testDate: '',
      finalFormat: '',
    }
  },
  filters: {
    charIndex: function (i) {
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


    this.getData(this.slug);
    this.userResponses = Array(this.quiz.questions.length).fill(null);
  },
  components: {FlipCountdown},
  methods: {
    finishCourse() {
      axios.post('/api/v1/generate-certificate', {
        course_id: this.testData.course.id,
      }).then(res => {
        if (res.data.status == 'success') {
          this.$toast.open({
            type: 'success',
            position: 'top-right',
            message: 'Your certificate is available in your dashboard',
            duration: 5000,
            dismissible: true
          });
        }
      }).catch(err => {
        console.log(err.response)
      })
    },
    testEnded() {
      this.submitTest();
    },
    restart: function () {
      //   this.questionIndex = 0;
      //   this.attempts++;


      this.getData(this.slug);
      this.$forceUpdate();
    },
    selectOption: function (index, id) {
      this.userResponses[this.questionIndex] = id
      this.$forceUpdate();
    },


    prev: function () {
      if (this.quiz.questions.length > 0) this.questionIndex--;
    },
    // Return "true" count in userResponses
    score: function () {
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
    getData(slug) {
      this.questionIndex = 0;
      this.testComplelete= false;
      this.testData = {questions:[]};
      this.resultData = 0;
      this.question_data = [];
      axios.post('/api/v1/single-test', {test: slug})
          .then(res => {
            this.attempts = res.data.response.test_result ? res.data.response.test_result.attempts : 0
            this.$parent.courseData = res.data.response
            this.courseData = res.data.response
            this.resultData = res.data.response.test_result ? res.data.response.test_result.score : 0
            this.testData = res.data.response.test
            this.testDate = new Date().toJSON().slice(0, 10);
            this.testTimer = this.testData.timer.date;
            this.quiz= {questions: []};
            for (var i = 0; i <= this.testData.questions.length - 1; i++) {
              let obj = {
                text: this.testData.questions[i].question,
                responses: []
              };

              for (var j = 0; j <= this.testData.questions[i].options.length - 1; j++) {
                var responses =
                    {
                      text: this.testData.questions[i].options[j].option_text,
                      correct: this.testData.questions[i].options[j].correct,
                      id: this.testData.questions[i].options[j].id
                    };
                obj.responses.push(responses)
              }
              this.quiz.questions.push(obj);
            }
            $('.course-title-header').text(this.testData.course.title)
            $('.close-lesson').attr('href', this.courseData.course_page)
            $('.course-progress').text(this.courseData.course_progress + ' %')
            $('.progress-bar').css('width', this.courseData.course_progress + '%');
            this.userResponses = Array(this.quiz.questions.length).fill(null);

          }).catch(err => {
        console.log(err)
      })
    },
    submitTest() {
      for (var i = 0; i < this.testData.questions.length; i++) {
        let questionObject = {
          question_id: this.testData.questions[i].id,
          ans_id: this.userResponses[i] ? this.userResponses[i] : null
        }
        this.question_data.push(questionObject)
      }
      axios.post('/api/v1/save-test', {
        test_id: this.testData.id,
        question_data: this.question_data
      }).then(res => {
        this.questionIndex++;
        this.attempts++;
        this.testComplelete = true;
        this.resultData = res.data.resultData
        if (this.resultData.test_result >= this.testData.min_grade) {
          this.$parent.courseData = res.data
          axios.post('/api/v1/course-progress', {
            model_type: "test", model_id: this.testData.id
          }).then(res => {
            let lastChapter = this.courseData.course_timeline[this.courseData.course_timeline.length - 1];
            if (lastChapter && lastChapter.test.model.slug === this.slug) {
              this.finishCourse();
            }

          })
        }
      }).catch((err) => {
        console.log(err)
      })
    },
    next: function () {
      if (this.questionIndex < this.quiz.questions.length - 1) {
        this.questionIndex++;
      } else {
        this.submitTest();
      }
    },
  }
}


</script>
