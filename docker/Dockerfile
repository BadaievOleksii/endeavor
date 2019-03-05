FROM centos:centos7

RUN yum clean all
RUN yum -y install epel-release git
RUN yum -y install ansible

RUN mkdir /srv/endeavor
ADD playbook.yml /srv/endeavor

WORKDIR /srv/endeavor
RUN ansible-galaxy install geerlingguy.repo-remi
RUN ansible-galaxy install geerlingguy.php
RUN ansible-galaxy install geerlingguy.composer

RUN ansible-playbook playbook.yml
