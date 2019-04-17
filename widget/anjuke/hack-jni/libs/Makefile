# $@--目标文件，$^--所有的依赖文件，$<--第一个依赖文件
export DYLD_LIBRARY_PATH=/usr/local/opt/unicorn/lib/:$DYLD_LIBRARY_PATH

LDFLAGS += $(shell pkg-config --libs glib-2.0) -lpthread -lm -lunicorn -I"${JAVA_HOME}/include/" -I"${JAVA_HOME}/include/darwin/"

%: %.c
	$(CC) $(CFLAGS) $^ $(LDFLAGS) -o $@

Sign.o: Sign.cpp
	$(CC) $(CFLAGS) $^ $(LDFLAGS) -o $@

all: test libSign.so

clean:
	rm -f *.o *.so test

