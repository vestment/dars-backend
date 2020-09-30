<template>

  <div class="player-video">
    <vue-plyr v-if="lessonVideo.src" :key="lessonVideo.src" ref="plyr" :emit="['ended','playing']"
              @playing="playerReadied" @ended="onPlayerEnded">
      <video :src="lessonVideo.src" v-if="lessonVideo.type == 'upload'" type="video/mp4">
      </video>
      <div v-else-if="lessonVideo.type == 'youtube'" data-plyr-provider="youtube"
           :data-plyr-embed-id="lessonVideo.src"></div>
      <div v-else-if="lessonVideo.type == 'vimeo'" data-plyr-provider="vimeo"
           :data-plyr-embed-id="lessonVideo.src"></div>
      <div v-else class="plyr__video-embed">
        {{ lessonVideo.src }}
      </div>
    </vue-plyr>

  </div>
</template>


<script>
// Similarly, you can also introduce the plugin resource pack you want to use within the component
// import 'some-videojs-plugin'

import axios from '../axios'
import 'vueditor/dist/style/vueditor.min.css'
import './lesson.css'


export default {
  name: 'player',
  data() {
    return {
      lessonIndex: '',
      lessonVideo: {
        type: 'video/mp4',
        src: ''
      },
      canFinishCourse: '',
      courseData: [],
      notes: [],
      newNote: {contentText: ''},
      current_note: {contentText: ''},
      downloadableMedia: {data: '', lesson: ''},
      slug: this.$route.params.slug ? this.$route.params.slug : this.slug,
      video: ''
    }
  },

  watch: {
    $route() {
      this.slug = this.$route.params.slug
      this.$refs.plyr.player.restart()
      this.$forceUpdate();
    },
    slug() {
      this.getData(this.slug)
    },
  },
  created() {
    this.getData(this.slug)
  },
  computed: {
    player() {
      return this.$refs.plyr.player
    }
  },
  methods: {
    onPlayerEnded($event) {
      axios.post('/api/v1/course-progress', {
        model_type: "lesson", model_id: this.courseData.lesson.id
      }).then(res => {

        this.getData(this.slug)
        this.$on('updateLesson', (courseData) => {
          console.log('updated info',courseData);
          let lastChapter = this.courseData.course_timeline[this.courseData.course_timeline.length - 1];
          if (courseData.next_lesson) {

            courseData.course_timeline.map(chapter => {
              chapter.lessons.filter(lesson => {

                if (courseData.next_lesson.model_id == lesson.model_id && lesson.canView == true) {
                  this.$router.push({name: 'player', params: {slug: lesson.model.slug}})
                }


              })
            });
          }
          if (!lastChapter.test && !courseData.next_lesson) {
            this.finishCourse();
          }
        });

      });
    },
    finishCourse() {
      axios.post('/api/v1/generate-certificate', {
        course_id: this.courseData.course.id,
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
    getData(slug) {
      axios.post('/api/v1/single-lesson', {lesson: slug})
          .then(res => {
            if (res.data.result) {
              this.courseData = res.data.result
              this.$parent.courseData = this.courseData
              this.$parent.type = 'player'
              if (this.courseData.lesson.media_video) {
                this.lessonVideo.type = this.courseData.lesson.media_video.type
                if (this.lessonVideo.type == 'youtube' || this.lessonVideo.type == 'vimeo') {
                  this.lessonVideo.src = this.courseData.lesson.media_video.file_name
                } else {
                  this.lessonVideo.src = this.courseData.lesson.media_video.url
                }
              }
              this.$emit('updateLesson',this.courseData)
              this.$forceUpdate();
              $('.course-title-header').text(this.courseData.course.title)
              $('.close-lesson').attr('href', this.courseData.course_page)
              $('.course-progress').text(this.courseData.course_progress + ' %')
              $('.progress-bar').css('width', this.courseData.course_progress + '%')
            }
          }).catch(err => {
        console.log(err)
      })
    },
    // player is ready
    playerReadied(player) {
      // this.$refs.plyr.player.restart()
      this.$forceUpdate();
      let myPluginCollection = document.getElementsByClassName('svg-embedded')

      let clonedDiv = myPluginCollection[0];
      if (clonedDiv) {
        player.target.appendChild(clonedDiv);
        setInterval(function () {
          var $div = $(clonedDiv),
              docHeight = $div.parent().height(),
              docWidth = $div.parent().width(),
              divHeight = $div.height(),
              divWidth = $div.width(),
              heightMax = docHeight - divHeight,
              widthMax = docWidth - divWidth;
          $div.show();
          $div.css({
            left: Math.floor(Math.random() * widthMax),
            top: Math.floor(Math.random() * heightMax)
          });
        }, 2 * 1000);
      }
    }
  }
}
</script>
<style>


.video-js {
  width: 100%;
}
</style>
