FROM ubuntu:bionic

ENV TZ=Europe/Moscow

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y privoxy tor net-tools

#CMD lsof -i -P -n | grep LISTEN

COPY ./privoxy/config /etc/privoxy
COPY ./tor/torrc /etc/tor
COPY ./tor/torsocks.conf /etc/tor

CMD service privoxy restart; service tor restart;
