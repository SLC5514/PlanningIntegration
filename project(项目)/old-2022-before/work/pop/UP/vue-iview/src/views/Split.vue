<template>
    <div class="demo-split">
        <div class="template-list">
            <div v-for="(v, i) in templates" :key="i" style="display:inline-block;"><img :src="v.thumb"></div>
        </div>
        <Slider v-model="tIndex" show-input :max="24" style="width:500px;"></Slider>
        <div class="template">
            <div v-for="(v, i) in templates[tIndex].grids"
                :key="templates[tIndex].id + '-' + i"
                :data-key="templates[tIndex].id + '-' + i"
                :class="{
                    item: true,
                    active: tKey === (templates[tIndex].id + '-' + i)
                }" :style="{
                    left: v.x + 'px',
                    top: v.y + 'px',
                    width: v.width + 'px',
                    height: v.height + 'px'
                }" @click="selectFn(templates[tIndex].id + '-' + i, $event)">
                    <img src="http://img.zcool.cn/community/0122a357de6d7e0000012e7e1f25e6.jpg@1280w_1l_2o_100sh.png"
                        :style="{
                            top: '22.5px',
                            left: '557.5px',
                            transform: 'scale(1) rotate(0deg) rotateX(0deg) rotateY(0deg)'
                        }">
                    <div class="remove">X</div>
                    <span class="test">{{templates[tIndex].id + '-' + i}}</span>
                </div>
        </div>
    </div>
</template>
<script>
    // 生成一个空的格子图片数据
    const genEmptyPhotoData = function () {
        return {
            isLocal: false, // 是否本地上传的图片
            width: 0, // 图片宽
            height: 0, // 图片高
            x: 0, // 图片左上角相对格子左上角 left
            y: 0, // 图片左上角相对格子左上角 top
            link: '', // 点击格子后跳转链接
            linkType: '1', // 链接类型: 1-网页, 2-文件
            description: '', // 图片描述
            big: '', // 大图
            small: '', // 小图
            brand: '', // 品牌名
            id: '', // 图片ID，款式图为对应款式id，本地图为上传后返回的id
            scale: 1.0, // 缩放比, 显示大小 : 实际大小
            rotation: 0, // 旋转角度
            rotationX: 0, // 是否沿X轴翻转
            rotationY: 0 // 是否沿Y轴翻转
        }
    };

    // 生成一个空的格子数据
    const genEmptyGridData = function () {
        return {
            width: 0, // 格子宽
            height: 0, // 格子高
            x: 0, // 格子左边界位置 left
            y: 0, // 格子上边界位置 top
            rw: 1,
            rh: 1,
            rx: 1,
            ry: 1,
            photo: genEmptyPhotoData()
        }
    };

    export default {
        data () {
            return {
                tIndex: 0,
                tKey: '',
                templates: [{"id":"7","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g1.png?12","grids":[{"x":0,"y":0,"width":2000,"height":1125}]},{"id":"8","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g2.png?12","grids":[{"x":0,"y":0,"width":997,"height":1125},{"x":1003,"y":0,"width":997,"height":1125}]},{"id":"9","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g3.png?12","grids":[{"x":0,"y":0,"width":997,"height":560},{"x":1003,"y":0,"width":997,"height":560},{"x":0,"y":566,"width":997,"height":559},{"x":1003,"y":566,"width":997,"height":559}]},{"id":"10","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g4.png?12","grids":[{"x":0,"y":0,"width":663,"height":1125},{"x":669,"y":0,"width":662,"height":1125},{"x":1337,"y":0,"width":663,"height":1125}]},{"id":"11","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g5.png?12","grids":[{"x":0,"y":0,"width":663,"height":560},{"x":669,"y":0,"width":662,"height":560},{"x":1337,"y":0,"width":663,"height":560},{"x":0,"y":566,"width":663,"height":559},{"x":669,"y":566,"width":662,"height":559},{"x":1337,"y":566,"width":663,"height":559}]},{"id":"12","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g6.png?12","grids":[{"x":0,"y":0,"width":495,"height":1125},{"x":501,"y":0,"width":496,"height":1125},{"x":1003,"y":0,"width":496,"height":1125},{"x":1505,"y":0,"width":495,"height":1125}]},{"id":"13","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g7.png?12","grids":[{"x":0,"y":0,"width":495,"height":560},{"x":1003,"y":0,"width":496,"height":560},{"x":1505,"y":0,"width":495,"height":560},{"x":501,"y":0,"width":496,"height":560},{"x":0,"y":566,"width":495,"height":559},{"x":501,"y":566,"width":496,"height":559},{"x":1003,"y":566,"width":496,"height":559},{"x":1505,"y":566,"width":495,"height":559}]},{"id":"14","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g8.png?12","grids":[{"x":0,"y":0,"width":997,"height":1125},{"x":1003,"y":0,"width":997,"height":560},{"x":1003,"y":566,"width":997,"height":559}]},{"id":"15","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g9.png?12","grids":[{"x":0,"y":0,"width":997,"height":560},{"x":0,"y":566,"width":997,"height":559},{"x":1003,"y":0,"width":997,"height":1125}]},{"id":"16","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g10.png?12","grids":[{"x":0,"y":0,"width":663,"height":371},{"x":669,"y":0,"width":662,"height":371},{"x":1337,"y":0,"width":663,"height":371},{"x":0,"y":377,"width":663,"height":371},{"x":669,"y":377,"width":662,"height":371},{"x":1337,"y":377,"width":663,"height":371},{"x":0,"y":754,"width":663,"height":371},{"x":669,"y":754,"width":662,"height":371},{"x":1337,"y":754,"width":663,"height":371}]},{"id":"17","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g11.png?12","grids":[{"x":0,"y":0,"width":997,"height":1125},{"x":1003,"y":0,"width":496,"height":560},{"x":1505,"y":0,"width":495,"height":560},{"x":1003,"y":566,"width":496,"height":559},{"x":1505,"y":566,"width":495,"height":559}]},{"id":"18","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g12.png?12","grids":[{"x":0,"y":0,"width":495,"height":560},{"x":501,"y":0,"width":496,"height":560},{"x":0,"y":566,"width":495,"height":559},{"x":501,"y":566,"width":496,"height":559},{"x":1003,"y":0,"width":997,"height":1125}]},{"id":"19","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g13.png?12","grids":[{"x":0,"y":0,"width":495,"height":1125},{"x":501,"y":0,"width":496,"height":560},{"x":1003,"y":0,"width":496,"height":560},{"x":1505,"y":0,"width":495,"height":560},{"x":501,"y":566,"width":496,"height":559},{"x":1003,"y":566,"width":496,"height":559},{"x":1505,"y":566,"width":495,"height":559}]},{"id":"20","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g14.png?12","grids":[{"x":0,"y":0,"width":495,"height":560},{"x":501,"y":0,"width":496,"height":560},{"x":1003,"y":0,"width":496,"height":560},{"x":0,"y":566,"width":495,"height":559},{"x":501,"y":566,"width":496,"height":559},{"x":1003,"y":566,"width":496,"height":559},{"x":1505,"y":0,"width":495,"height":1125}]},{"id":"21","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g15.png?12","grids":[{"x":0,"y":0,"width":663,"height":560},{"x":1337,"y":0,"width":663,"height":560},{"x":669,"y":0,"width":662,"height":1125},{"x":0,"y":566,"width":663,"height":559},{"x":1337,"y":566,"width":663,"height":559}]},{"id":"25","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g19.png?12","grids":[{"x":0,"y":0,"width":495,"height":371},{"x":501,"y":0,"width":496,"height":371},{"x":1003,"y":0,"width":496,"height":371},{"x":1505,"y":0,"width":495,"height":371},{"x":0,"y":377,"width":495,"height":371},{"x":501,"y":377,"width":496,"height":371},{"x":1003,"y":377,"width":496,"height":371},{"x":1505,"y":377,"width":495,"height":371},{"x":0,"y":754,"width":495,"height":371},{"x":501,"y":754,"width":496,"height":371},{"x":1003,"y":754,"width":496,"height":371},{"x":1505,"y":754,"width":495,"height":371}]},{"id":"26","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g20.png?12","grids":[{"x":0,"y":0,"width":395,"height":371},{"x":401,"y":0,"width":395,"height":371},{"x":802,"y":0,"width":396,"height":371},{"x":1204,"y":0,"width":395,"height":371},{"x":1605,"y":0,"width":395,"height":371},{"x":0,"y":377,"width":395,"height":371},{"x":401,"y":377,"width":395,"height":371},{"x":802,"y":377,"width":396,"height":371},{"x":1204,"y":377,"width":395,"height":371},{"x":1605,"y":377,"width":395,"height":371},{"x":0,"y":754,"width":395,"height":371},{"x":401,"y":754,"width":395,"height":371},{"x":802,"y":754,"width":396,"height":371},{"x":1204,"y":754,"width":395,"height":371},{"x":1605,"y":754,"width":395,"height":371}]},{"id":"27","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/26.jpg","grids":[{"x":0,"y":0,"width":495,"height":1060},{"x":501,"y":0,"width":496,"height":1060},{"x":1003,"y":0,"width":496,"height":1060},{"x":1505,"y":0,"width":495,"height":1060},{"x":0,"y":1066,"width":495,"height":59},{"x":501,"y":1066,"width":496,"height":59},{"x":1003,"y":1066,"width":496,"height":59},{"x":1505,"y":1066,"width":495,"height":59}]},{"id":"28","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/25.jpg","grids":[{"x":0,"y":0,"width":997,"height":560},{"x":0,"y":566,"width":997,"height":559},{"x":1003,"y":0,"width":496,"height":560},{"x":1505,"y":0,"width":495,"height":560},{"x":1003,"y":566,"width":496,"height":559},{"x":1505,"y":566,"width":495,"height":559}]},{"id":"29","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/24.jpg","grids":[{"x":0,"y":0,"width":395,"height":1060},{"x":401,"y":0,"width":395,"height":1060},{"x":802,"y":0,"width":396,"height":1060},{"x":1204,"y":0,"width":395,"height":1060},{"x":1605,"y":0,"width":395,"height":1060},{"x":0,"y":1066,"width":395,"height":59},{"x":401,"y":1066,"width":395,"height":59},{"x":802,"y":1066,"width":396,"height":59},{"x":1204,"y":1066,"width":395,"height":59},{"x":1605,"y":1066,"width":395,"height":59}]},{"id":"30","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/23.jpg","grids":[{"x":0,"y":0,"width":496,"height":1125},{"x":502,"y":0,"width":495,"height":560},{"x":502,"y":566,"width":495,"height":559},{"x":1003,"y":0,"width":496,"height":1125},{"x":1505,"y":0,"width":495,"height":560},{"x":1505,"y":566,"width":495,"height":559}]},{"id":"31","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/22.jpg","grids":[{"x":0,"y":0,"width":395,"height":560},{"x":401,"y":0,"width":395,"height":560},{"x":802,"y":0,"width":396,"height":560},{"x":1204,"y":0,"width":395,"height":560},{"x":1605,"y":0,"width":395,"height":560},{"x":0,"y":566,"width":395,"height":559},{"x":401,"y":566,"width":395,"height":559},{"x":802,"y":566,"width":396,"height":559},{"x":1204,"y":566,"width":395,"height":559},{"x":1605,"y":566,"width":395,"height":559}]},{"id":"32","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/21.jpg","grids":[{"x":0,"y":0,"width":395,"height":1125},{"x":401,"y":0,"width":395,"height":1125},{"x":802,"y":0,"width":396,"height":1125},{"x":1204,"y":0,"width":395,"height":1125},{"x":1605,"y":0,"width":395,"height":1125}]},{"id":"33","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/27.png","grids":[{"x":0,"y":0,"width":495,"height":1125},{"x":1003,"y":0,"width":496,"height":560},{"x":1505,"y":0,"width":495,"height":1125},{"x":501,"y":0,"width":496,"height":560},{"x":501,"y":566,"width":496,"height":559},{"x":1003,"y":566,"width":496,"height":559}]},{"id":"34","thumb":"http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/28.jpg","grids":[{"x":0,"y":0,"width":395,"height":1125},{"x":401,"y":0,"width":395,"height":560},{"x":802,"y":0,"width":396,"height":1125},{"x":1204,"y":0,"width":395,"height":560},{"x":1605,"y":0,"width":395,"height":1125},{"x":401,"y":566,"width":395,"height":559},{"x":1204,"y":566,"width":395,"height":559}]}]
            }
        },
        created() {
            console.log(this.templates.length)
            console.log(this.templates[this.tIndex])
            document.removeEventListener('click', this.documentClick)
            document.addEventListener('click', this.documentClick)
        },
        methods: {
            documentClick() {
                this.tKey = ''
            },
            selectFn(val, e) {
                e.stopPropagation()
                this.tKey = val
            }
        }
    }
</script>
<style lang="less">
    .template {
        width: 2000px;
        height: 1125px;
        transform: translate(-400px, -270px) scale(0.5);
        outline: 5px solid #000;
        background: #eee;
        cursor: auto;
        position: relative;
        margin-bottom: 100px;
        .item {
            border: 1px solid #999;
            background: #ccc;
            box-sizing: border-box;
            position: absolute;
            font-size: 30px;
            .test {
                position: absolute;
                left: 0;
                top: 0;
            }
            .remove {
                display: none;
            }
            img {
                position: absolute;
                cursor: move;
            }
            &.active {
                border-color: red;
                .remove {
                    display: block;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    border-radius: 50%;
                    background: red;
                    position: absolute;
                    right: 10px;
                    top: 10px;
                    font-size: 30px;
                    text-align: center;
                    color: #fff;
                    cursor: pointer;
                }
            }
        }
    }
</style>
