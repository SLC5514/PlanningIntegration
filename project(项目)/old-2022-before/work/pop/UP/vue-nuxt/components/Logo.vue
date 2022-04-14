<template>
  <div class="VueToNuxtLogo">
    <div class="Triangle Triangle--two" />
    <div class="Triangle Triangle--one" />
    <div class="Triangle Triangle--three" />
    <div class="Triangle Triangle--four" />
  </div>
</template>

<style lang="less" scope>
@multiple: 6.125;

.VueToNuxtLogo {
  vertical-align: middle;
  display: inline-block;
  animation: turn 2s linear forwards 1s;
  transform: rotateX(180deg);
  position: relative;
  top: -2px;
  overflow: hidden;
  height: 180px / @multiple;
  width: 245px / @multiple;
}

.Triangle {
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 0;
}

.Triangle--one {
  border-left: 105px / @multiple solid transparent;
  border-right: 105px / @multiple solid transparent;
  border-bottom: 180px / @multiple solid #41b883;
}

.Triangle--two {
  top: 30px / @multiple;
  left: 35px / @multiple;
  animation: goright 0.5s linear forwards 3.5s;
  border-left: 87.5px / @multiple solid transparent;
  border-right: 87.5px / @multiple solid transparent;
  border-bottom: 150px / @multiple solid #3b8070;
}

.Triangle--three {
  top: 60px / @multiple;
  left: 35px / @multiple;
  animation: goright 0.5s linear forwards 3.5s;
  border-left: 70px / @multiple solid transparent;
  border-right: 70px / @multiple solid transparent;
  border-bottom: 120px / @multiple solid #35495e;
}

.Triangle--four {
  top: 120px / @multiple;
  left: 70px / @multiple;
  animation: godown 0.5s linear forwards 3s;
  border-left: 35px / @multiple solid transparent;
  border-right: 35px / @multiple solid transparent;
  border-bottom: 60px / @multiple solid #fff;
}

@keyframes turn {
  100% {
    transform: rotateX(0deg);
  }
}

@keyframes godown {
  100% {
    top: 180px / @multiple;
  }
}

@keyframes goright {
  100% {
    left: 70px / @multiple;
  }
}
</style>
