## 高等数学

### 三角函数
  - P14

### 极限
- $\lim C=C$
- $\lim _{x \rightarrow x_{0}} x=x_{0}$
- $\lim _{x \rightarrow \infty} \frac{1}{x}=0$
- $\lim _{x \rightarrow 0} \frac{\sin x}{x}=1$
- $\lim _{x \rightarrow \infty}\left(1+\frac{a}{x}\right)^{b x}=e^{a b}$

### 等阶无穷小代换
- $\sin \mathrm{x} \sim \mathrm{x}$
- $\tan \mathrm{x} \sim \mathrm{x}$
- $\arctan \mathrm{x} \sim \mathrm{x}$
- $\arcsin \mathrm{x} \sim \mathrm{x}$
- $1-\cos x \sim \frac{x^{2}}{2}$
- $\ln (1+x) \sim x$
- $e^{x}-1 \sim x$
- $\sqrt{1+x}-1 \sim \frac{x}{2}$

### 导数
- $y=C \sim y^{\prime}=0$
- $y=a^{x} \sim y^{\prime}=a^{x} \ln a$
- $y=\log _{a} x \sim y^{\prime}=\frac{1}{x \ln a}$
- $y=x^{n} \sim y^{\prime}=n x^{n-1}$
- $y=\sin x \sim y^{\prime}=\cos x$
- $y=\cos x \sim y^{\prime}=-\sin x$

### 导数四则运算法则
- $(\mathrm{u} \pm \mathrm{v})^{\prime}=\mathrm{u}^{\prime} \pm \mathrm{v}^{\prime}$
- $(u \cdot v)^{\prime}=u^{\prime} \cdot v+u \cdot v^{\prime}$
- $(\mathrm{cu})^{\prime}=\mathrm{c} \cdot \mathrm{u}^{\prime}$
- $\left(\frac{u}{v}\right)^{\prime}=\frac{u ^{\prime} \cdot v-u \cdot v^{\prime}}{v^{2}}(v \neq 0)$
- $(\mathrm{u} \cdot \mathrm{v} \cdot \mathrm{w})^{\prime}=\mathrm{u}^{\prime} \cdot \mathrm{v} \cdot \mathrm{w}+\mathrm{u} \cdot \mathrm{v}^{\prime} \cdot \mathrm{w}+\mathrm{u} \cdot \mathrm{v} \cdot \mathrm{w}^{\prime}$

### 洛必达法则
- $\lim _{x \rightarrow a} \frac{f(x)}{g(x)}=\lim _{x \rightarrow a} \frac{f^{\prime}(x)}{g^{\prime}(x)}=A$

## 概率论与数理统计

### 事件运算的生质

- 交换律： $A \bigcup B=B \bigcup A ； A \bigcap B=B \bigcap A$；
- 结合律： $A \bigcup(B \bigcup C)=(A \bigcup B) \bigcup C$；
$A \cap(B \cap C)=(A \cap B) \bigcap C$；
- 分配律： $A \cup(B \cap C)=(A \cup B) \cap(A \cup C)$；
$A \cap(B \cup C)=(A \cap B) \bigcup(A \cap C)$；
- 德摩根律: $\overline{A \cup B}=\bar{A} \cap \bar{B}；\overline{A \bigcap B}=\bar{A} \bigcup \bar{B}$；


### 概率的性质
- $P(\varnothing)=0, P(S)=1$
- 逆事件的概率：对于任意事件 $A$, 有 $P(\bar{A})=1-P(A)$;
- 减法公式: 设 $A, B$ 是两个事件, $P(A-B)=P(A \bar{B})=P(A)-P(A B)$ 。特别地, 若 $A \subset B$, 则有 $P(A) \leq P(B)$, 且 $P(B-A)=P(B)-P(A)$;
- 加法公式：对于任意两随机事件 $A, B$ 有：$P(A \cup B)=P(A)+P(B)-P(A B)$
- 三个事件的概率加法公式:$P(A \cup B \cup C)=P(A)+P(B)+P(C)-P(A B)-P(B C)-P(A C)+P(A B C)$

### 古典概型
$$
P(A)=\frac{A \text { 中基本的事件个数 } x}{\text { 基本事件总数 } n}
$$
- 排列公式：$A_n^m=\frac{n !}{(n-m) !}$
- 组合公式：$C_n^m=\frac{A_n^m}{m !}=\frac{n !}{m !(n-m) !}$

### 条件概率
$$
P(B \mid A)=\frac{P(A B)}{P(A)}, \quad(P(A)>0)
$$
- $0 \leq P(B \mid A) \leq 1$;
- $P(S \mid A)=1$;
- $P(\bar{A} \mid B)=1-P(A \mid B)$;
- $P(A+B \mid C)=P(A \mid C)+P(B \mid C)-P(A B \mid C)$;
- $\text { 若 } P(A)>0, \quad P(B \mid A)=\frac{P(A B)}{P(A)} \Rightarrow P(A B)=P(B \mid A) P(A) \text {. }$

### 全概率公式:
$$
P(A)=\sum_{i=1}^n P\left(A B_i\right)=\sum_{i=1}^n P\left(A \mid B_i\right) P\left(B_i\right)
$$

### 贝叶斯公式
$$
P\left(B_{i} \mid A\right)=\frac{P\left(B_{i} A\right)}{P(A)}=\frac{P\left(A \mid B_{i}\right) P\left(B_{i}\right)}{\sum_{j=1}^{n} P\left(A \mid B_{j}\right) P\left(B_{j}\right)}
$$

### 事件的独立性
设  A  和  B  是两事件, 若满足  P(A B)=P(A) P(B) , 则称事件  A  和  B  相互独立。

### 常用随机变量的数学期望和方差

| 分布 | 参数 | 分布律或概率密度 | 数学期望 | 方差 |
| :----- | :----- | :----- | :----- | :----- |
| 离散，0-1分布 | p | $P\{X=k\}=p^{k}(1-p)^{1-k}；(k=0,1)$ | p | $p(1-p)$ |
| 离散，二项分布$B(n,p)$ | n,p | $P\{X=k\}=C_{n}^{k}p^{k}(1-p)^{n-k}$ | np | $n p(1-p)$ |
| 离散，泊松分布$P(\lambda)$ | $\lambda$ | $P\{X=k\}={\frac{\lambda^{k}e^{-{\lambda}}}{k!}}$ | $\lambda$ | $\lambda$ |
| 连续，均匀分布$U(a,b)$ | a<b | $f(x)={\frac{1}{b-a}}；(a\lt x\lt b)$ | $\textstyle{\frac{a+b}{2}}$ | $\frac{(b-a)^{2}}{12}$ |
| 连续，正态分布$N(\mu,\sigma^{2})$ | $\mu_,\sigma$ | $f(x)={\frac{1}{{\sqrt{2\pi}}\sigma}}e^{-{\frac{(x-\mu)^{2}}{2\sigma^{2}}}}$ | $\mu$ | $\sigma^{2}$ |
| 连续，指数分布$E(\lambda)$ | $\lambda$ | $f(x)={\left\{\begin{array}{l l}{\lambda e^{-\lambda x},\ \ x\gt 0}\\ {0}，{其他}\end{array}\right.}$ | $\frac{1}\lambda$ | $\frac{1}{\lambda^{2}}$ |

### 泊松定理
设$\lambda\gt0$是一个常数，n是任意正整数，设$n p_{n}=\lambda$，则对任一固定的非负整数k,有
$$\operatorname*{lim}_{n\rightarrow\infty}C_{n}^{k}p_{n}^{k}(1-p_{n})^{n-k}=\frac{\lambda^{k}e^{-\lambda}}{k!}$$
如果随机变量$X{\sim}B(n,p)$，当n很大，p很小时，X近似服从参数$\lambda=n p$的泊松分布，即
$$P(X=k)=C_{n}^{k}p^{k}(1-p)^{n-k}\approx{\frac{(n p)^{k}e^{-np}}{k!}}$$

### 随机变量的分布函数
![P39](../../images/math/%E9%9A%8F%E6%9C%BA%E5%8F%98%E9%87%8F%E7%9A%84%E5%88%86%E5%B8%83%E5%87%BD%E6%95%B0.jpg)
- $X \sim U(a,b)$的分布函数
$$
F(x)= 
\left\{ 
    \begin{array}{lc}
        0 & x \lt a \\
        \frac{x-a}{b-a} & a\leq x\lt b \\
        1 & x \gt b \\
    \end{array}
\right.
$$
- $X \sim E(\lambda)$的分布函数
$$
F(x)= 
\left\{ 
    \begin{array}{lc}
        1 - e^{-\lambda x} & x \gt 0 \\
        0 & 其他 \\
    \end{array}
\right.
$$
> 泊松分布，一段时间内事件发生的数量，平均值为$\lambda$；指数分布，两个事件发生的间隔，平均值为$\frac{1}{\lambda}$，具有无记忆性。

### 正态分布
- 关于$x=\mu$对称；
- 在$x=\mu\pm\sigma$处有拐点；
- 若$X{\sim}N(0,1)$，则称X服从标准正态分布；

### 标准正态分布
- 关于x=0对称，$f(-x)=f(x)$;
- 分布函数$\Phi(-x)=1-\Phi(x)$；
- 可通过$Z={\frac{X-\mu}{\sigma}}\sim N(0,1)$将正态分布转换为标准正态分布；

### 连续型随机变量及其概率密度
定义：如果对于随机变量X的分布函数F(x)，存在非负可积函数f(x)，使对任实数x有
$$F(x)=\int_{-\infty}^{x}f(t)\,d t$$
则称X为连续型随机变量，f(x) 称为X的概率密度函数，简称概率密度。
- $f(x)\geq0$;
- $\textstyle\int_{-\infty}^{+\infty}f(x)d x=1$;
- $P(x_{1}\lt X\leq x_{2})=F(x_{2})-F(x_{1})=\textstyle\int_{x_1}^{x_2}f(x)dx$，$P(x_{1}\lt X\leq x_{2})=P(x_{1}\leq X\leq x_{2})=P(x_{1}\leq X\lt x_{2})=P(x_{1}\lt X\lt x_{2})$;
- 若$f(x)$在点x处连续，则有$F^{\prime}(x)=f(x)$；

### 二维离散型随机变量
- 联合分布律
$$P\{X=x_{i},Y=y_{j}\}=p_{ij},(i,j=1,2,\cdots)$$
- 联合分布函数
$$F(x,y)=P\{X\leq x,Y\leq y\}=\sum_{x_{i}\leq x}\sum_{y_{j}\leq y}p_{ij}(i,j=1,2,\cdots)$$
- 边缘分布律
$$P_{i}=\sum_{j}p_{ij},(j=1,2,\cdots)$$
- 边缘分布函数
$$F_{X}(x)=P\{X\leq x\}$$
- 条件分布
$$P(X=i|Y=j)={\frac{P(X=i,Y=j)}{P(Y=j)}}$$
- 独立性
$$P_{ij}=P_{i}\times P_{j},(i,j=1,2,\cdots) \Leftrightarrow F(x,y)=F_{x}(x)\cdot F_{Y}(y)$$

### 二维连续性随机变量
- 联合分布函数
$$F(x,y)=P\{X\leq x,Y\leq y\}=\int_{-\infty}^{y}\int_{-\infty}^{x}f(u,v)d u d v$$
若f(x,y)在点(x,y)处连续，则有${\frac{{\partial}^{2}F(x,y)}{\partial x\partial y}}=f(x,y)$
- 边缘分布函数
$$F_{X}(x)=F(x,+\infty)=\int_{-\infty}^{x}\Big[\int_{-\infty}^{+\infty}f(x,y)dy\Big]dx$$
- 边缘概率密度
$$f_{x}(x)=[F_{X}(x)]^{\prime}=\int_{-\infty}^{+\infty}f(x,y)dy$$
- 条件分布
$$f_{X,Y}(X=x|Y=y)={\frac{f(x,y)}{f_{Y}(y)}}；f_{Y}(y)\gt 0$$
- 独立性
$$f(x,y)=f_{X}(x)\cdot f_{Y}(y)$$

### 二维连续型随机变量函数的分布
$$F_{Z}(z)=P\{Z\leq z\}=\mathop{\int\int}\limits_{g(x,y)\leq z}f(x,y)dxdy$$
> 根据函数增减性判断变量x,y的区间

### 期望
- 离散型随机变量的数学期望
$$E(x)=\sum_{i=1}^{\infty}x_{i}p_{i}$$
- 连续型随机变量的数学期望
$$E(X)=\int_{-\infty}^{+\infty}xf(x)dx$$
- 一维离散型随机变量函数的数学期望
$$E(Y)=\sum_{i=1}^{\infty}g(x_{i})p_{i}$$
- 一维连续型随机变量函数的数学期望
$$E(Y)=\int_{-\infty}^{+\infty}g(x)f_{X}(x)dx$$
- 二维离散型随机变量函数的数学期望
$$E(Z)=E[g(X,Y)]=\sum_{i=1}^{\infty}\sum_{j=1}^{\infty}g(x_{i},y_{j})p_{i j}$$
- 二维连续型随机变量函数的数学期望
$$E(Z)=E[g(X,Y)]=\int_{-\infty}^{+\infty}\int_{-\infty}^{+\infty}g(x,y)f(x,y)d xdy$$

### 随机变量数学期望的性质
- 设C为常数，则有E(C)=C;
- 设X为一随机变量，且E(X)存在，C为常数，则有E(CX)= CE(X);
- 设X与Y是两个随机变量，则有E(X +Y)= E(X)+ E(Y);
- 设X与Y是两个随机变量，则有E(aX土bY)= aE(X)土bE(Y);
- 设X与Y相互独立，则有E(XY)= E(X)E(Y), (反之不成立)；

### 方差
- 计算公式
$$D(X)=E\{[X-E(X)]^2\}=E(X^2)-[E(X)]^2$$
- 标准差或均方差
$$\sqrt{D(X)}$$

### 方差的性质
- 设C为常数，则$D(C)=0$；
- 如果X为随机变量，C为常数，则$D(CX)=C^2D(X)$；
- 如果X为随机变量，C为常数，则有$D(X +C)= D(X)$；
- 若X,Y 相互独立，则$D(X{\pm}Y)= DX + DY$；
- 更一般地: $D(aX +bY)=a^2 D(X)+ b^2D(Y)$；

### 协方差
- 计算公式
$$Cov(X,Y)=E[(X-E X)(Y-E Y)]=E(X Y)-E(X)E(Y)$$

### 协方差的性质:
- $Cov(X,Y)= Cov(Y,X)$;
- $Cov(X,Y + Z) = Cov(X,Y)+ Cov(X,Z)$;
- $Cov(aX ,bY)= abCov(X,Y)$,其中a,b为任意常数;
- $Cov(C,X)=0$, 其中C为任意常数;
- $Cov(aX_{1}{\pm}bX_{2},Y)= aCov(X_{1},Y){\pm}bCov(X_{2},Y)$;
- 如果X与Y相互独立，则$Cov(X,Y)=0$;
- $D(X +Y)= D(X)+ D(Y)+ 2Cov(X,Y)$;

### 相关系数
设(X,Y)是二维随机变量，且X和Y的方差均存在，且都不为零，则称
$$\rho_{XY}={\frac{{Cov}(X,Y)}{\sqrt{DX}\sqrt{DY}}}$$
目的是为了消除量纲（单位）因素的影响，参照
$$X \sim N(\mu,\sigma^{2})；{\frac{X-\mu}{\sigma}}={\frac{X-E(X)}{\sqrt{D(X)}}}$$
$$X^*={\frac{X-E(X)}{\sqrt{D(X)}}}，Y^*={\frac{Y-E(Y)}{\sqrt{D(Y)}}}$$
$$Cov(X^*,Y^*)=Cov({\frac{X-EX}{\sqrt{DX}}},{\frac{Y-EY}{\sqrt{DY}}})={\frac{{Cov}(X,Y)}{\sqrt{DX}\sqrt{DY}}}$$

### 相关系数的性质
- $|\rho_{XY}|\leq1$.当$\rho_{XY}=0$时，称X与Y不相关.
- 随机变量X与Y不相关的等价说法:当DX≠0,DY≠0时，
$$\rho_{XY}=0\Leftrightarrow Cov(X,Y)=0\Leftrightarrow E(X Y)=E(X)E(Y)\Leftrightarrow D(X\pm Y)=D X+D Y$$
- 若随机变量X,Y相互独立则X与Y不相关，但反之不一定（不线性相关不表示相互独立）.

### 大数定律及中心极限定理
- 切比雪夫不等式
$$P\{|X-E(X)|\leq{\varepsilon}\}\leq{\frac{D(X)}{\varepsilon^{2}}}；{\varepsilon} \gt 0；P\{\mid X-E(X)\}\lt \varepsilon\}\geq1-{\frac{D(X)}{\varepsilon^{2}}}$$
- 依概率收敛定义：设$X_{i},X_{2},\cdots X_{n},\cdots$是一个随机变量序列，$a$是一个常数，如果对于任意给定的正数$\varepsilon$，有$$\operatorname*{lim}_{n\to\infty}P\{{\vert}X_{n}-a\vert\lt\varepsilon\}=1$$则称随机变量序列$X_{i},X_{2},\cdots X_{n},\cdots$依概率收敛于$a$，记为$X_{n}{\xrightarrow{P}}a$。

| 名词 | 大数定律 | 注释 |
| :----- | :----- | :----- |
| 切比雪夫大数定律 | 设随机变量序列$X_{1},X_{2},\cdots$两两不相关，且方差均存在，又存在常数$D(X_{i})\leq C(i=1,2,\cdots)$, 则对任意$\varepsilon\gt0$,有$$\operatorname*{lim}_{n\to\infty}P\Big\{\Big\vert{\frac{1}{n}\sum_{i=1}^{\infty}X_i-\frac{1}{n}\sum_{i=1}^{\infty}E(X_i)}\Big\vert\lt\varepsilon\Big\}=1$$ | 在定理条件下，有$$\frac{1}{n}\sum_{i=1}^{\infty}X_i-\frac{1}{n}\sum_{i=1}^{\infty}E(X_i){\xrightarrow{P}}0$$ |
| 辛钦大数定律 | 设随机变量序列$X_{1},X_{2},\cdots$独立同分布，且数学期望存在，即$E(X)=\mu(i=1,2,\cdots)$，则对任意$\varepsilon\gt0$，有$$\operatorname*{lim}_{n\to\infty}P\Big\{\Big\vert{\frac{1}{n}\sum_{i=1}^{\infty}X_i-\mu}\Big\vert\lt\varepsilon\Big\}=1$$ | 在定理条件下，有$$\frac{1}{n}\sum_{i=1}^{\infty}X_i{\xrightarrow{P}}\mu$$ |
| 伯努利大数定律 | 设$\mu_n$是n次独立试验中事件A发生的次数，而p是事件A在每次试验中发生的概率，则对任意$\varepsilon\gt0$,有$$\operatorname*{lim}_{n\to\infty}P\Big\{\Big\vert{\frac{\mu_n}{n}-p}\Big\vert\lt\varepsilon\Big\}=1$$ | 事件A发生的频率依概率收敛于事件A的概率，即$$\frac{\mu_n}{n}\xrightarrow{P}p$$|

- 林德伯格-莱维(独立同分布) 中心极限定理

设随机变量$X_{1},X_{2},\cdots,X_{n},\cdots$相互独立同分布，且期望和方差均存在，即$E(X_{k})=\mu,D(X_{k})=\sigma^{2}\gt 0,(k=1,2,3,\cdots)$,则随机变量之和$\sum_{k=1}^nX_k$;的标准化变量
$$Y_{n}=\frac{\sum_{k=1}^{n}X_{k}-E\left(\sum_{k=1}^{n}X_{k}\right)}{\sqrt{D\left(\sum_{k=1}^{n}X_k\right)}}=\frac{\sum_{k=1}^{n}X_{k}-n\mu}{\sqrt{n}\sigma}$$
的分布函数$F_n(x)$对于任意$x$满足（近似标准正态分布）:
$$\operatorname*{lim}_{n\to\infty}F_n(x)=\operatorname*{lim}_{n\to\infty}P\left\{\frac{\sum_{k=1}^{n}X_{k}-n\mu}{\sqrt{n}\sigma}{\le}x\right\}=\int_{-\infty}^{x}\frac{1}{\sqrt{2\pi}}^{e^{-\frac{t^2}{2}}}dt=\Phi(x)$$

- 棣莫弗-拉普拉斯中心极限定理

设随机变量$X_{1},X_{2},\cdots,X_{n},\cdots$独立同分布，且服从0-1分布，即$P(X_{k}=1)=p,P(X_{k}=0)=1-p,(0\lt p\lt 1,k=1,2,3,\cdots)$，则对于任意x满足（参考0-1分布的期望和方差）:
$$\operatorname*{lim}_{n\to\infty}F_n(x)=\operatorname*{lim}_{n\to\infty}P\left\{\frac{\sum_{k=1}^{n}X_{k}-np}{\sqrt{np(1-p)}}{\le}x\right\}=\int_{-\infty}^{x}\frac{1}{\sqrt{2\pi}}^{e^{-\frac{t^2}{2}}}dt=\Phi(x)$$









## 线性代数
