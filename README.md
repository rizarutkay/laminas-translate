# Laminas Google Translate(Api)

Projeyi github'tan clone'ladıktan sonra sırasıyla composer update , docker-compose build  ve  docker-compose up -d  cmd komutlarıyla docker üzerinden ayağa kaldırıp

localhost:8000 adresinden tarayıcıda açabilirsiniz.Çeviri Google Translate Api V3(projects) kullanılmıştır. Sayfadaki eventler'de jquery ağırlıklı olmuştur ve ajax 

çağrılarının sonuçlarına göre değişiklikler sayfaya yansımaktadır. Yapılan çevirileri geçmişte görebilir ve istediklerinizi kaydedilenlere alabilirsiniz.

Geçmiş listesinde daha önceden kaydedilenlere aldığınız  çeviriyi geri alıp kaydedilenlerden kaldırabilir ya da kaydedilenler listesinden kaldırabilirsiniz.

Geçmiş listesinle kaydedilenlerde olan çeviriler yeşil gözükmedir. Aynı şekilde kaydedilenlerden kaldırılan çevirinin geçmişteki rengi defaulta dönmektedir. Memory 

caching için Predis kütüphanesiyle Redis kullanılmıştır.10 saniyede birden çok çeviri yapılmaya çalışıldığında alert ile hata göreceksiniz. Geçmişteki çeviriler 

browser kapatıldıktan sonra gider ama kaydediler bellekte kalmaktadır. Kaynak dil kısmında dili algıla seçili olmadığı sürece metin / çeviri metin arasında geçiş

yapılabilir. Bir çeviri yapıldıktan sonra çeviri butonu disable olur ve kaynak dil,çevilecek dil ya da metin değiştiğinde tekrar kullanılabilir. Ajax çağrıları tek
 
bir fonksiyon üzerinden yapılmaktadır. Yapılmak istenen çeviri geçmişte ve ya kaydedilenlerde varsa google apiyi çağırmaz.





