#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <Ultrasonic.h>

#define ON HIGH
#define OFF LOW
#define led1 D3

const char* ssid = "Przz";
const char* password = "ineedyou";

WiFiClient wifiClient;

const int trigPin = D4;       // Pin trigger sensor HC-SR04 terhubung ke pin D4 pada WeMos D1R1
const int echoPin = D5;       // Pin echo sensor HC-SR04 terhubung ke pin D5 pada WeMos D1R1
const int waterSensorPin = A0; // Pin water sensor terhubung ke pin A0 pada WeMos D1R1
const int buzzerPin = D8;     // Pin buzzer terhubung ke pin D8 pada WeMos D1R1

Ultrasonic ultrasonic(trigPin, echoPin); // Buat objek Ultrasonic

void setup() {
  Serial.begin(115200);
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println(WiFi.localIP());

  pinMode(led1, OUTPUT);
  pinMode(waterSensorPin, INPUT);
  pinMode(buzzerPin, OUTPUT);
}

void loop() {
   int distance = ultrasonic.read(); // Membaca data dari sensor HC-SR04
  Serial.println("Distance: " + String(distance) + " cm");
  
  int waterLevel = analogRead(waterSensorPin); // Membaca data dari water sensor
  Serial.println("Water Level: " + String(waterLevel));
  
  if (waterLevel > 1000) {
    digitalWrite(led1, ON);
    digitalWrite(buzzerPin, ON);
    Serial.println("LED dan Buzzer ON");
  } else {
    digitalWrite(led1, OFF);
    digitalWrite(buzzerPin, OFF);
    Serial.println("LED dan Buzzer OFF");
  }
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    Serial.print("[HTTP] begin...\n");
    String link;
    link = F("http://192.168.190.231/flood_detection/flood.php?resikoBanjir=");
    link += String(waterLevel, 6);
    link += "&ketinggianAir=";
    link += String(distance, 6);
    Serial.printf("Link : %s\n", link);
    http.begin(wifiClient, link);
    Serial.print("[HTTP] GET...\n");

    int httpCode = http.GET();
    Serial.print("HTTPCODE: ");
    Serial.println(httpCode);

   
    http.end();
  } else {
    Serial.println("Delay...");
  }
  
  delay(1000);
}
