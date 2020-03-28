ARG ELASTIC_VERSION
FROM docker.elastic.co/elasticsearch/elasticsearch:$ELASTIC_VERSION

RUN chown -R 1000:1000 /usr/share/elasticsearch/data

WORKDIR /usr/share/elasticsearch/data

CMD /usr/local/bin/docker-entrypoint.sh eswrapper