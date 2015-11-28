path('D:\matlab\silenceRemoval',path);
clear all;

FolderName=uigetdir
PathName=strcat(FolderName,'\');
name1=strcat(PathName,'samsung_GT-I8262.wav');
name2=strcat(PathName,'samsung_GT-I9003.wav');
name3=strcat(PathName,'samsung_GT-S5360.wav');
name4=strcat(PathName,'Sony_C1904.wav');
name5=strcat(PathName,'Sony_C2104.wav');
name6=strcat(PathName,'Sony_C5302.wav');
name7=strcat(PathName,'g.wav');

%signal 1;;
[segments,fs]=detectVoiced(name1);
    segmentsize=size(segments);
    ip1=[];
    for i=1:segmentsize(1,2)
        ip1=[ip1;segments{1,i}];
    end
    
    
%signal 2 
[segments,fs]=detectVoiced(name2);
    segmentsize=size(segments);
    ip2=[];
    for i=1:segmentsize(1,2)
        ip2=[ip2;segments{1,i}];
    end
    
%signal 3
[segments,fs]=detectVoiced(name3);
    segmentsize=size(segments);
    ip3=[];
    for i=1:segmentsize(1,2)
        ip3=[ip3;segments{1,i}];
    end

%signal 4
[segments,fs]=detectVoiced(name4);
    segmentsize=size(segments);
    ip4=[];
    for i=1:segmentsize(1,2)
        ip4=[ip4;segments{1,i}];
    end
    
 %signal 5
[segments,fs]=detectVoiced(name5);
    segmentsize=size(segments);
    ip5=[];
    for i=1:segmentsize(1,2)
        ip5=[ip5;segments{1,i}];
    end
 
%signal 6
[segments,fs]=detectVoiced(name6);
    segmentsize=size(segments);
    ip6=[];
    for i=1:segmentsize(1,2)
        ip6=[ip6;segments{1,i}];
    end
    
% %signal 7
% [segments,fs]=detectVoiced(name7);
%     segmentsize=size(segments);
%     ip7=[];
%     for i=1:segmentsize(1,2)
%         ip7=[ip7;segments{1,i}];
%     end
    
    
figure(1);
hold off;  


for i=2:10000


%signal 1
j=i-1;
x = ip1((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,1);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name1);


%signal 2
j=i-1;
x = ip2((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,2);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name2);


%signal 3
j=i-1;
x = ip3((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,3);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name3);



%signal 4
j=i-1;
x = ip4((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,4);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name4);



%signal 5
j=i-1;
x = ip5((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,5);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name5);


%signal 6
j=i-1;
x = ip6((j*320):(i*320));
NFFT = length(x);
X = fft(x,NFFT);
xaxis = linspace(0,1,NFFT)*fs;
y = abs(X);

subplot(3,3,6);
fig=plot(xaxis,y);
ylim([0 25]);
xlabel('frequency');
ylabel('magnitude');
title(name6);


% %signal 7
% j=i-1;
% x = ip7((j*320):(i*320));
% NFFT = length(x);
% X = fft(x,NFFT);
% xaxis = linspace(0,1,NFFT)*fs;
% y = abs(X);
% 
% subplot(3,3,7);
% fig=plot(xaxis,y);
% ylim([0 25]);
% xlabel('frequency');
% ylabel('magnitude');
% title(name7);

% Name xlabel as ‘frequency (Hz)’ and ylabel as ‘Magnitude’
pause(0.001);

end

