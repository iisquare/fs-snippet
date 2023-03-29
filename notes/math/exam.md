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

- 洛必达法则
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
$
P(A)=\frac{A \text { 中基本的事件个数 } x}{\text { 基本事件总数 } n}
$
- 排列公式：$A_n^m=\frac{n !}{(n-m) !}$
- 组合公式：$C_n^m=\frac{A_n^m}{m !}=\frac{n !}{m !(n-m) !}$

### 条件概率
$
P(B \mid A)=\frac{P(A B)}{P(A)}, \quad(P(A)>0)
$
- $0 \leq P(B \mid A) \leq 1$;
- $P(S \mid A)=1$;
- $P(\bar{A} \mid B)=1-P(A \mid B)$;
- $P(A+B \mid C)=P(A \mid C)+P(B \mid C)-P(A B \mid C)$;
- $\text { 若 } P(A)>0, \quad P(B \mid A)=\frac{P(A B)}{P(A)} \Rightarrow P(A B)=P(B \mid A) P(A) \text {. }$

### 全概率公式:
$
P(A)=\sum_{i=1}^n P\left(A B_i\right)=\sum_{i=1}^n P\left(A \mid B_i\right) P\left(B_i\right)
$

### 贝叶斯公式
$
P\left(B_{i} \mid A\right)=\frac{P\left(B_{i} A\right)}{P(A)}=\frac{P\left(A \mid B_{i}\right) P\left(B_{i}\right)}{\sum_{j=1}^{n} P\left(A \mid B_{j}\right) P\left(B_{j}\right)}
$

### 事件的独立性
设  A  和  B  是两事件, 若满足  P(A B)=P(A) P(B) , 则称事件  A  和  B  相互独立。

### 常用随机变量的数学期望和方差




## 线性代数
