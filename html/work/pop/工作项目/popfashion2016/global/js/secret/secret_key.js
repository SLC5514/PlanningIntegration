// 对称加密--加密
function aes_encrypt(plainText, AES_KEY) {
    if (typeof plainText == 'object') {
        // 如果传递的参数为json对象，要将对象先转为字符串
        plainText = JSON.stringify(plainText);
    }
    var encrypted = CryptoJS.AES.encrypt(plainText, CryptoJS.enc.Utf8.parse(AES_KEY), {
        iv: CryptoJS.enc.Utf8.parse('1234567887654301')
    });
    return CryptoJS.enc.Base64.stringify(encrypted.ciphertext);
};
// 对称加密--解密
function aes_decrypt(plainText, AES_KEY) {
    var decrypted = CryptoJS.AES.decrypt(plainText, CryptoJS.enc.Utf8.parse(AES_KEY), {
        iv: CryptoJS.enc.Utf8.parse('1234567887654301')
    });
    return decrypted.toString(CryptoJS.enc.Utf8);
};
// 非对称加密--解密 （注意）
function rsa_decrypt(encrypted) {
    var decrypt = new JSEncrypt();
    decrypt.setPrivateKey(pri_key());
    data_decrypted = decrypt.decrypt(decodeURIComponent(encrypted));
    return data_decrypted;
};
// 非对称加密--加密
function rsa_encrypt(str) {
    var encrypt = new JSEncrypt();
    encrypt.setPublicKey(pub_key());
    var res = encrypt.encrypt(str);
    // base64位加密替换，避免空格转义 " + " => " %2B "
    data_str = encodeURI(res).replace(/\+/g, '%2B');
    return data_str;
};

function pub_key() {
    var pub_key = '-----BEGIN PUBLIC KEY-----';
        pub_key += 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1053Mcqn6WH+f+liJid6';
        pub_key += 'rcEaJOO94OSQsWzIQSGWM4yoVOVg85YKhW5JlwAHH53Zd8sASLk0ypQv5ze68zeS';
        pub_key += '3PSU5jrzuzDUEqZvEQaOHfKcUXRx3C61iR9iTnA6YCZLlIX3CnRJzYWCJNRjV+qp';
        pub_key += 'GHGXr73iXRhLgqfQhC5dlMisbr3GqnGtPNVOBDYGtGgKS4mpaYzpO60kunooKmdH';
        pub_key += 'Avw5eS3UkIPixj4B0mZkhn9VZ9mOgBRgs9Lo/Ihc/hJXAk3kZvzQjIpzKrYAqHYp';
        pub_key += '4cKyAfWCegA0G0fGo2aJWqpFrAg25FTDXFf0DRDz/AIEvJu+EfjuWjJxz02MuWsi';
        pub_key += 'hwIDAQAB';
        pub_key += '-----END PUBLIC KEY-----';
    return pub_key;
};
