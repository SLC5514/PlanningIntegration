class Runing {
    constructor() {
        const mother = new Mother()
        mother.narrate(new Book())
        mother.narrate(new Newspaper())
    }
}

// interface IReader { getContent }

class Book {
    getContent() {
        return '很久很久以前有一个阿拉伯的故事……'
    }
}

class Newspaper {
    getContent() {
        return '林书豪38+7领导尼克斯击败湖人……'
    }
}

class Mother {
    narrate(reader) {
        console.log('妈妈开始讲故事')
        console.log(reader.getContent())
    }
}

new Runing()
