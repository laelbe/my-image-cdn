# My custom cdn edge.
Link : https://blog.lael.be/post/7605

This code will help you to create your custom CDN Edge.

이 코드를 사용하면 쉽게 당신만의 CDN 엣지를 만들 수 있습니다.  
이 코드는 CPU나 메모리 자원을 거의 소모하지 않습니다. 디스크 용량이 많은 인스턴스를 준비해주세요.  
가상서버 뿐만 아니라, 웹호스팅 환경에서도 구동 가능합니다.

## 기능
- 확장자 필터링 (기본 : png, jpg, jpeg, gif, css, js)
- 리퍼러 체크/핫링크 방지
- 캐시 적중 표시

## 설치 방법

### 아파치
- `config.php` 파일을 수정하세요. $original_host 값을 수정해야 합니다.
- `.htaccess` 파일을 수정하세요. 이 리소스를 호출할 호스트를 등록해주세요.

### NGINX
- `config.php` 파일을 수정하세요. $original_host 값을 수정해야 합니다.
- 이 페이지로 이동해서 (https://blog.lael.be/demo-generator/imagecdn/my-example-site.com.php) 쓰여진 구문을 적용하세요.

## 사용 안내
Apache 환경에서 구축하는게 더 쉽습니다.(서버 파일을 수정할 필요없음)  
가끔씩 이 코드의 구조를 개선합니다.(튜닝)  
보안이나 성능 문제가 없기 때문에, 이미 이 코드를 적용하였고, 잘 동작하고 있다면, 굳이 최신버전으로 패치할 필요는 없습니다.

## 유지 보수 (for advanced user)
디스크 공간 절약을 위해 캐시된 파일을 주기적으로 지워주는 것이 좋습니다.
### crontab
`10 4 * * * /usr/bin/find /home/imagecdn/www -mindepth 2 -atime +5 -type f \( -iname \*.png -o -iname \*.jpg -o -iname \*.jpeg -o -iname \*.gif \) | xargs rm 1>/dev/null 2>/dev/null`  
폴더 위치(/home/imagecdn/www)와 접근 조건(+5) 부분을 수정하세요.
