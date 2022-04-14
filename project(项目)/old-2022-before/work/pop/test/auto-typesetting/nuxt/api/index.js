// import http from "@/utils/http";
// import qs from "qs";
// import md5 from "md5";
// import moment from "moment";

// function getHash() {
//   return md5("Pop_Puzzle" + moment().format("YYYY-MM-DD"));
// }

/**
 * 获取自动排版模板列表
 */
export function getTemplates() {
  // return http({
  //   url:
  //     "http://www.pop-fashion.com/puzzle/getPuzzleTemplates/?t=" +
  //     Math.random(),
  //   method: "POST",
  //   data: qs.stringify({ hash: getHash() })
  // });
  return new Promise(function(resolve) {
    resolve({
      code: 1,
      data: [
        {
          id: "7",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g1.png?12",
          grids: [{ x: 0, y: 0, width: 2000, height: 1125 }]
        },
        {
          id: "8",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g2.png?12",
          grids: [
            { x: 0, y: 0, width: 997, height: 1125 },
            { x: 1003, y: 0, width: 997, height: 1125 }
          ]
        },
        {
          id: "9",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g3.png?12",
          grids: [
            { x: 0, y: 0, width: 997, height: 560 },
            { x: 1003, y: 0, width: 997, height: 560 },
            { x: 0, y: 566, width: 997, height: 559 },
            { x: 1003, y: 566, width: 997, height: 559 }
          ]
        },
        {
          id: "10",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g4.png?12",
          grids: [
            { x: 0, y: 0, width: 663, height: 1125 },
            { x: 669, y: 0, width: 662, height: 1125 },
            { x: 1337, y: 0, width: 663, height: 1125 }
          ]
        },
        {
          id: "11",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g5.png?12",
          grids: [
            { x: 0, y: 0, width: 663, height: 560 },
            { x: 669, y: 0, width: 662, height: 560 },
            { x: 1337, y: 0, width: 663, height: 560 },
            { x: 0, y: 566, width: 663, height: 559 },
            { x: 669, y: 566, width: 662, height: 559 },
            { x: 1337, y: 566, width: 663, height: 559 }
          ]
        },
        {
          id: "12",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g6.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 1125 },
            { x: 501, y: 0, width: 496, height: 1125 },
            { x: 1003, y: 0, width: 496, height: 1125 },
            { x: 1505, y: 0, width: 495, height: 1125 }
          ]
        },
        {
          id: "13",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g7.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 560 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 1505, y: 0, width: 495, height: 560 },
            { x: 501, y: 0, width: 496, height: 560 },
            { x: 0, y: 566, width: 495, height: 559 },
            { x: 501, y: 566, width: 496, height: 559 },
            { x: 1003, y: 566, width: 496, height: 559 },
            { x: 1505, y: 566, width: 495, height: 559 }
          ]
        },
        {
          id: "14",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g8.png?12",
          grids: [
            { x: 0, y: 0, width: 997, height: 1125 },
            { x: 1003, y: 0, width: 997, height: 560 },
            { x: 1003, y: 566, width: 997, height: 559 }
          ]
        },
        {
          id: "15",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g9.png?12",
          grids: [
            { x: 0, y: 0, width: 997, height: 560 },
            { x: 0, y: 566, width: 997, height: 559 },
            { x: 1003, y: 0, width: 997, height: 1125 }
          ]
        },
        {
          id: "16",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g10.png?12",
          grids: [
            { x: 0, y: 0, width: 663, height: 371 },
            { x: 669, y: 0, width: 662, height: 371 },
            { x: 1337, y: 0, width: 663, height: 371 },
            { x: 0, y: 377, width: 663, height: 371 },
            { x: 669, y: 377, width: 662, height: 371 },
            { x: 1337, y: 377, width: 663, height: 371 },
            { x: 0, y: 754, width: 663, height: 371 },
            { x: 669, y: 754, width: 662, height: 371 },
            { x: 1337, y: 754, width: 663, height: 371 }
          ]
        },
        {
          id: "17",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g11.png?12",
          grids: [
            { x: 0, y: 0, width: 997, height: 1125 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 1505, y: 0, width: 495, height: 560 },
            { x: 1003, y: 566, width: 496, height: 559 },
            { x: 1505, y: 566, width: 495, height: 559 }
          ]
        },
        {
          id: "18",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g12.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 560 },
            { x: 501, y: 0, width: 496, height: 560 },
            { x: 0, y: 566, width: 495, height: 559 },
            { x: 501, y: 566, width: 496, height: 559 },
            { x: 1003, y: 0, width: 997, height: 1125 }
          ]
        },
        {
          id: "19",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g13.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 1125 },
            { x: 501, y: 0, width: 496, height: 560 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 1505, y: 0, width: 495, height: 560 },
            { x: 501, y: 566, width: 496, height: 559 },
            { x: 1003, y: 566, width: 496, height: 559 },
            { x: 1505, y: 566, width: 495, height: 559 }
          ]
        },
        {
          id: "20",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g14.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 560 },
            { x: 501, y: 0, width: 496, height: 560 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 0, y: 566, width: 495, height: 559 },
            { x: 501, y: 566, width: 496, height: 559 },
            { x: 1003, y: 566, width: 496, height: 559 },
            { x: 1505, y: 0, width: 495, height: 1125 }
          ]
        },
        {
          id: "21",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g15.png?12",
          grids: [
            { x: 0, y: 0, width: 663, height: 560 },
            { x: 1337, y: 0, width: 663, height: 560 },
            { x: 669, y: 0, width: 662, height: 1125 },
            { x: 0, y: 566, width: 663, height: 559 },
            { x: 1337, y: 566, width: 663, height: 559 }
          ]
        },
        {
          id: "25",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g19.png?12",
          grids: [
            { x: 0, y: 0, width: 495, height: 371 },
            { x: 501, y: 0, width: 496, height: 371 },
            { x: 1003, y: 0, width: 496, height: 371 },
            { x: 1505, y: 0, width: 495, height: 371 },
            { x: 0, y: 377, width: 495, height: 371 },
            { x: 501, y: 377, width: 496, height: 371 },
            { x: 1003, y: 377, width: 496, height: 371 },
            { x: 1505, y: 377, width: 495, height: 371 },
            { x: 0, y: 754, width: 495, height: 371 },
            { x: 501, y: 754, width: 496, height: 371 },
            { x: 1003, y: 754, width: 496, height: 371 },
            { x: 1505, y: 754, width: 495, height: 371 }
          ]
        },
        {
          id: "26",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/g20.png?12",
          grids: [
            { x: 0, y: 0, width: 395, height: 371 },
            { x: 401, y: 0, width: 395, height: 371 },
            { x: 802, y: 0, width: 396, height: 371 },
            { x: 1204, y: 0, width: 395, height: 371 },
            { x: 1605, y: 0, width: 395, height: 371 },
            { x: 0, y: 377, width: 395, height: 371 },
            { x: 401, y: 377, width: 395, height: 371 },
            { x: 802, y: 377, width: 396, height: 371 },
            { x: 1204, y: 377, width: 395, height: 371 },
            { x: 1605, y: 377, width: 395, height: 371 },
            { x: 0, y: 754, width: 395, height: 371 },
            { x: 401, y: 754, width: 395, height: 371 },
            { x: 802, y: 754, width: 396, height: 371 },
            { x: 1204, y: 754, width: 395, height: 371 },
            { x: 1605, y: 754, width: 395, height: 371 }
          ]
        },
        {
          id: "27",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/26.jpg",
          grids: [
            { x: 0, y: 0, width: 495, height: 1060 },
            { x: 501, y: 0, width: 496, height: 1060 },
            { x: 1003, y: 0, width: 496, height: 1060 },
            { x: 1505, y: 0, width: 495, height: 1060 },
            { x: 0, y: 1066, width: 495, height: 59 },
            { x: 501, y: 1066, width: 496, height: 59 },
            { x: 1003, y: 1066, width: 496, height: 59 },
            { x: 1505, y: 1066, width: 495, height: 59 }
          ]
        },
        {
          id: "28",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/25.jpg",
          grids: [
            { x: 0, y: 0, width: 997, height: 560 },
            { x: 0, y: 566, width: 997, height: 559 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 1505, y: 0, width: 495, height: 560 },
            { x: 1003, y: 566, width: 496, height: 559 },
            { x: 1505, y: 566, width: 495, height: 559 }
          ]
        },
        {
          id: "29",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/24.jpg",
          grids: [
            { x: 0, y: 0, width: 395, height: 1060 },
            { x: 401, y: 0, width: 395, height: 1060 },
            { x: 802, y: 0, width: 396, height: 1060 },
            { x: 1204, y: 0, width: 395, height: 1060 },
            { x: 1605, y: 0, width: 395, height: 1060 },
            { x: 0, y: 1066, width: 395, height: 59 },
            { x: 401, y: 1066, width: 395, height: 59 },
            { x: 802, y: 1066, width: 396, height: 59 },
            { x: 1204, y: 1066, width: 395, height: 59 },
            { x: 1605, y: 1066, width: 395, height: 59 }
          ]
        },
        {
          id: "30",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/23.jpg",
          grids: [
            { x: 0, y: 0, width: 496, height: 1125 },
            { x: 502, y: 0, width: 495, height: 560 },
            { x: 502, y: 566, width: 495, height: 559 },
            { x: 1003, y: 0, width: 496, height: 1125 },
            { x: 1505, y: 0, width: 495, height: 560 },
            { x: 1505, y: 566, width: 495, height: 559 }
          ]
        },
        {
          id: "31",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/22.jpg",
          grids: [
            { x: 0, y: 0, width: 395, height: 560 },
            { x: 401, y: 0, width: 395, height: 560 },
            { x: 802, y: 0, width: 396, height: 560 },
            { x: 1204, y: 0, width: 395, height: 560 },
            { x: 1605, y: 0, width: 395, height: 560 },
            { x: 0, y: 566, width: 395, height: 559 },
            { x: 401, y: 566, width: 395, height: 559 },
            { x: 802, y: 566, width: 396, height: 559 },
            { x: 1204, y: 566, width: 395, height: 559 },
            { x: 1605, y: 566, width: 395, height: 559 }
          ]
        },
        {
          id: "32",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/21.jpg",
          grids: [
            { x: 0, y: 0, width: 395, height: 1125 },
            { x: 401, y: 0, width: 395, height: 1125 },
            { x: 802, y: 0, width: 396, height: 1125 },
            { x: 1204, y: 0, width: 395, height: 1125 },
            { x: 1605, y: 0, width: 395, height: 1125 }
          ]
        },
        {
          id: "33",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/27.png",
          grids: [
            { x: 0, y: 0, width: 495, height: 1125 },
            { x: 1003, y: 0, width: 496, height: 560 },
            { x: 1505, y: 0, width: 495, height: 1125 },
            { x: 501, y: 0, width: 496, height: 560 },
            { x: 501, y: 566, width: 496, height: 559 },
            { x: 1003, y: 566, width: 496, height: 559 }
          ]
        },
        {
          id: "34",
          thumb:
            "http://img1.fm.pop-fashion.com/upload/puzzle/local/20180225/28.jpg",
          grids: [
            { x: 0, y: 0, width: 395, height: 1125 },
            { x: 401, y: 0, width: 395, height: 560 },
            { x: 802, y: 0, width: 396, height: 1125 },
            { x: 1204, y: 0, width: 395, height: 560 },
            { x: 1605, y: 0, width: 395, height: 1125 },
            { x: 401, y: 566, width: 395, height: 559 },
            { x: 1204, y: 566, width: 395, height: 559 }
          ]
        }
      ]
    });
  });
}
