FROM alpine:3.11

RUN apk add --no-cache --virtual python3-dev nano curl && \
	apk add --no-cache --update python3 && \
	pip3 install --upgrade pip && \
	mkdir /app

RUN echo -e "alias python='python3'\nalias pip='pip3'" >> /etc/profile && source /etc/profile

COPY config/requirements.txt /app/requirements.txt

WORKDIR /app

RUN pip install -r requirements.txt

ENV FLASK_APP=server.py
ENV FLASK_ENV=development

ENTRYPOINT ["flask"]

CMD ["run", "--host", "0.0.0.0", "--port", "8000"]