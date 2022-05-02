# My custom cdn edge.
Link : https://blog.lael.be/post/7605

This code will help you to create your custom CDN Edge.

이 코드를 사용하면 쉽게 당신만의 CDN 엣지를 만들 수 있습니다.  
이 코드는 CPU나 메모리 자원을 거의 소모하지 않습니다. 디스크 용량이 많은 인스턴스를 준비해주세요.  
가상서버 뿐만 아니라, 웹호스팅 환경에서도 구동 가능합니다.

## 기능 (Features)
- 확장자 필터링 (기본 : png, jpg, jpeg, gif, css, js)
- 리퍼러 체크/핫링크 방지
- 캐시 적중 표시
- 커스텀 헤더

## 서버 요구사항 (Server Requirements)
- Any version of PHP
- Sufficient disk space to store the image.

## 설치 방법 (How to install)

### 아파치
- `config.php` 파일을 수정하세요. **$original_host** 값을 수정해야 합니다.  
파일 확장자 필터링 값을 확인해주세요. 원한다면 **$allowed_extension[]** 값을 수정해주세요.
- `.htaccess` 파일을 수정하세요. 이 리소스를 호출할 호스트를 등록해주세요.

### NGINX
- `config.php` 파일을 수정하세요. **$original_host** 값을 수정해야 합니다.  
파일 확장자 필터링 값을 확인해주세요. 원한다면 **$allowed_extension[]** 값을 수정해주세요.
- 이 페이지로 이동해서 (https://blog.lael.be/demo-generator/imagecdn/my-example-site.com.php) 쓰여진 구문을 적용하세요.

## 유지 보수 (Maintenance)
`remove_garbage.sh` 스크립트를 실행하면 지난 5일간 사용하지 않은 캐시 파일이 삭제됩니다.  
다음의 예시와 같이 crontab 을 설정하여, 매일 오전 4:10 에 실행하도록 하는 것이 좋습니다.

### crontab
<pre>10 4 * * * bash <i>/home/myuser1/</i>remove_garbage.sh 1>/dev/null 2>/dev/null</pre>

## 참고 사항 (Note)
서버 파일을 수정하지 않아도 되고, 웹호스팅 환경에서도 사용할 수 있는 Apache 환경에서의 구축을 권장합니다.  
가끔씩 이 코드의 구조를 개선합니다.(튜닝)  
보안이나 성능 문제가 없기 때문에, 이미 이 코드를 적용하였고, 잘 동작하고 있다면, 굳이 최신버전으로 패치할 필요는 없습니다.
