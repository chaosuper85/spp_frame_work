# spp_frame_work
spp_frame_work and demo project 

spp_frame_work:17uplibs
demoe_project:demoProject


目前框架还不是特别完善，待完善


参考的nginx的vhost配置

server {
        listen       80;
        server_name  debug.17house.com;


        set $root  /Users/chaosuper/17house/code_git/debugProject/demoProject/public;

        root $root;

        location / {
                index  index.html index.htm index.php spp.php;
        }

        location ~ \.php$ {
                try_files $uri @spp;
                fastcgi_pass   127.0.0.1:9000;
                fastcgi_index  spp.php;
                fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                include        fastcgi_params;
        }

        location @spp {
                internal;
                fastcgi_pass   127.0.0.1:9000;
                fastcgi_index  spp.php;
                fastcgi_param  SCRIPT_FILENAME  $document_root/spp.php;
                include        fastcgi_params;
        }

}

