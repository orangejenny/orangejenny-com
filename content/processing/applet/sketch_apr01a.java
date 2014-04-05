import processing.core.*; 
import processing.xml.*; 

import java.applet.*; 
import java.awt.*; 
import java.awt.image.*; 
import java.awt.event.*; 
import java.io.*; 
import java.net.*; 
import java.text.*; 
import java.util.*; 
import java.util.zip.*; 
import java.util.regex.*; 

public class sketch_apr01a extends PApplet {

// vars for canvas
int canvasWidth = 1150;
int canvasHeight = 600;
int backgroundColor = 0xffffffff;
int defaultSecondsPerFrame = 60;
int secondsPerFrame = 0;

// fonts
PFont font12, font18, font24, font30;

// vars for drawing race
int peopleSize = 1;
float mileLengthInPixels = 40;      // float to avoid integer division
int raceLengthInPixels = (int) (26.2f * mileLengthInPixels);
int raceHeightInPixels = 35;
int startLineX = (canvasWidth - raceLengthInPixels) / 2;
int startLineY = 340;

// vars for drawing elevation chart
Point chartOrigin = new Point(startLineX, canvasHeight - 30);
float chartScale = 0.25f;

// vars for drawing top elements
int topMargin = 35;

PointFloat[] elevations = generateElevations();
Person[] people = generatePeople();
Person user = new Person("4:22:00", 0);
int elapsedTime = 0;
int numFinishers = 0;
String userFinishedString = "";
int userFinishedRank = 0;

// GUI
boolean locked = false;
SquareButton playButton, pauseButton, resetButton;
HScrollBar speedSlider, userSlider;
int GUI_BASE_COLOR = 0xff3333ff;
int GUI_HIGHLIGHT_COLOR = 0xffcccccc;
int GUI_THIRD_COLOR = 0xffFFFF48;
int TEXT_GREY = 0xff666666;

public void setup() {  
  // canvas setup
  size(canvasWidth, canvasHeight);
  background(backgroundColor);
  
  frameRate(30);

  // font setup  
  font12 = loadFont("Verdana-12.vlw");
  font18 = loadFont("Verdana-18.vlw");
  font24 = loadFont("Verdana-24.vlw");
  font30 = loadFont("Verdana-30.vlw");

  //textAlign(CENTER);
  fill(0);
  textFont(font24, 24);
  text("Running with the Masses",chartOrigin.x, topMargin);
  //text("Running with the Masses",canvasWidth / 2, topMargin);
  fill(TEXT_GREY);
  textFont(font18, 18);
  text("2009 Boston Marathon", chartOrigin.x, topMargin + 25);
  //text("2009 Boston Marathon", canvasWidth / 2, topMargin + 25);
  
  drawElevationChart();
  
  int buttonX = chartOrigin.x;
  int buttonY = 75;
  playButton = new SquareButton(buttonX, buttonY, SQUARE_BUTTON_TYPE_PLAY);
  buttonX += playButton.size + 17;
  pauseButton = new SquareButton(buttonX, buttonY, SQUARE_BUTTON_TYPE_PAUSE);
  buttonX += playButton.size + 17;
  resetButton = new SquareButton(buttonX, buttonY, SQUARE_BUTTON_TYPE_RESET);
  
  speedSlider = new HScrollBar(startLineX, 155, .25f);
  userSlider = new HScrollBar(startLineX, 210, 1 - (user.timeToFinish / 26.2f - 270) / (1200 - 270.0f));
  
  smooth();
}

public void draw() {
  updateGUI(mouseX, mouseY);

  displayGUI();

  if (numFinishers == people.length + 1) {
    return;
  }

  // draw mile demarcations
  fill(backgroundColor);
  rect(0, startLineY - 35, width, raceHeightInPixels + 70);
  drawMileMarks(startLineY - 30, raceHeightInPixels + 60, false);

  // everybody run
  textFont(font24, 24);
  stroke(0, 0, 0, 128);
  noFill();
  int x = 0;
  int y = 0;
  int minX = Integer.MAX_VALUE;
  int maxX = Integer.MIN_VALUE;
  float minMile = Integer.MAX_VALUE;
  float maxMile = Integer.MIN_VALUE;
  boolean finished = false;
  strokeWeight(peopleSize);
  for (int i = 0; i < people.length; i++) {
    finished = people[i].run(secondsPerFrame);
    minMile = Math.min(minMile, people[i].currentMile);
    maxMile = Math.max(maxMile, people[i].currentMile);
    if (finished) {
      numFinishers++;
    }
  }
  finished = user.run(secondsPerFrame);
  minMile = Math.min(minMile, user.currentMile);
  maxMile = Math.max(maxMile, user.currentMile);
  if (finished) {
    numFinishers++;
    fill(0);
    textAlign(CENTER);
    userFinishedString = secondsToHHMMSS(elapsedTime);
    userFinishedRank = numFinishers;
    //text("Finished in " + userFinishedString, canvasWidth / 2, topMargin + 25);
    noFill();
  }

  // draw color band under runners
  noStroke();
  int alphaValue = Math.max(0, (int) ((maxMile - minMile) * 128 / 26.2f));
  fill(0, 0, 255, 64 - alphaValue / 2);
  rect(minMile * mileLengthInPixels + startLineX - 1, startLineY - 1, (maxMile - minMile) * mileLengthInPixels + 2, raceHeightInPixels + 2);

  // draw runners
  stroke(0, 0, 0, 128);
  for (int i = 0; i < people.length; i++) {
    x = (int) (mileLengthInPixels * people[i].currentMile);
    y = (i * peopleSize / 10) % (raceHeightInPixels + 1);
    if (people[i].currentMile > 26.2f) {
      if (people[i].rank % 2 == 1) {
        y += (int) random(0, 15);
      }
      else {
        y -= (int) random(0, 15);
      }
    }
    point(x + startLineX, y + startLineY);
  }

  // draw user
  x = (int) (mileLengthInPixels * user.currentMile);
  y = raceHeightInPixels / 2;
  stroke(GUI_THIRD_COLOR);
  fill(0xffffff00);
  fill(GUI_THIRD_COLOR);
  ellipse(x + startLineX, y + startLineY, 5, 5);
  noFill();
  if (frameCount % 30 < 10) {
    ellipse(x + startLineX, y + startLineY, 10, 10);
  }
  else if (frameCount % 30 < 20) {
    ellipse(x + startLineX, y + startLineY, 15, 15);
  }
  else {
    ellipse(x + startLineX, y + startLineY, 20, 20);
  }  

  strokeWeight(1.5f);
  line(x + startLineX, startLineY - 30, x + startLineX, startLineY);

  // draw moving clock
  noStroke();
  fill(backgroundColor);
  rect(0, startLineY - 70, canvasWidth, 40);
  fill(GUI_THIRD_COLOR);
  drawRoundedRect(x + startLineX - 35, startLineY - 70, 70, 35, 5);
  triangle(x + startLineX - 30, startLineY - 35, 
            x + startLineX + 30, startLineY - 35, 
            x + startLineX, startLineY - 30);
  fill(0);
  textAlign(CENTER);
  textFont(font12, 12);
  String myClock = "";
  if (userFinishedString != "") {
    myClock = userFinishedString;
  }
  else {
    myClock = secondsToHHMMSS(elapsedTime);
  }
  text(myClock, x + startLineX, startLineY - 40);
  text("My Time", x + startLineX - 3, startLineY - 55);

  fill(backgroundColor);
  rect(canvasWidth * 2 / 3, topMargin + 30, canvasWidth / 3, 130);

  // draw stats
  fill(0);
  textFont(font18, 18);
  textAlign(RIGHT);
  int intervalY = 25;
  text("Overall Time", 980, playButton.y + 13);
  text("Finishers", 980, playButton.y + 13 + intervalY);
  text("My Time", 980, speedSlider.ypos + 13);
  text("My Rank", 980, speedSlider.ypos + 13 + intervalY);

  // draw clock & finishers
  text(secondsToHHMMSS(elapsedTime), startLineX + raceLengthInPixels, playButton.y + 13);
  text(nfc(numFinishers), startLineX + raceLengthInPixels, playButton.y + 13 + intervalY);
  if (userFinishedString != "") {
    text(userFinishedString, startLineX + raceLengthInPixels, speedSlider.ypos + 13);
  }
  else {
    text("?", startLineX + raceLengthInPixels, speedSlider.ypos + 13);
  }
  if (userFinishedString != "") {
    text(nfc(userFinishedRank), startLineX + raceLengthInPixels, speedSlider.ypos + 13 + intervalY);
  }
  else {
    text("?", startLineX + raceLengthInPixels, speedSlider.ypos + 13 + intervalY);
  }

  if (numFinishers < people.length + 1) {
    elapsedTime += secondsPerFrame;
  }
}

public void updateGUI(int x, int y)
{
  if(locked == false) {
    playButton.update();
    pauseButton.update();
    resetButton.update();
    userSlider.update();
    speedSlider.update();
  } 
  else {
    locked = false;
  }

  int minSecondsPerFrame = 5;
  int maxSecondsPerFrame = 60;
  defaultSecondsPerFrame = (int) (speedSlider.getPercent() * (maxSecondsPerFrame - minSecondsPerFrame) + minSecondsPerFrame);
  if (secondsPerFrame > 0 && secondsPerFrame != defaultSecondsPerFrame) {
    secondsPerFrame = defaultSecondsPerFrame;
  }
  
  int secondsPerMile = (int) ((1 - userSlider.getPercent()) * (1200 - 270) + 270);
  if (user.timeToFinish != 26.2f * secondsPerMile) {
    user.updatePace(secondsPerMile);
  }

  if(mousePressed) {
    if(playButton.pressed()) {
      secondsPerFrame = defaultSecondsPerFrame;
    }
    if (pauseButton.pressed()) {
      secondsPerFrame = 0;
    }
    if (resetButton.pressed() || playButton.pressed() && numFinishers == people.length + 1) {
      elapsedTime = 0;
      numFinishers = 0;
      finished = false;
      user.reset();
      noStroke();
      fill(backgroundColor);
      rect(canvasWidth / 3, topMargin, canvasWidth / 3, 30);
      for (int i = 0; i < people.length; i++) {
        people[i].reset();
      }
      userFinishedString = "";
      userFinishedRank = 0;
    }
  }
}

public void displayGUI() {
  fill(backgroundColor);
  
  rect(playButton.x, playButton.y, resetButton.x - playButton.x + playButton.size, playButton.size);
  rect(userSlider.xpos - 5, userSlider.ypos - 5, userSlider.swidth + 10, userSlider.sheight + 25);
  rect(speedSlider.xpos - 5, speedSlider.ypos - 5, speedSlider.swidth + 10, speedSlider.sheight + 25);
  
  playButton.display();
  pauseButton.display();
  resetButton.display();
  userSlider.display();
  speedSlider.display();
  
  // draw labels
  fill(TEXT_GREY);
  textFont(font12, 12);
  textAlign(CENTER);
  text("Simulation speed", speedSlider.xpos + speedSlider.swidth / 2, speedSlider.ypos + speedSlider.sheight + 15);
  
  String userPace = secondsToMMSS((int) (user.timeToFinish / 26.2f)) + "/mile";
  text("My Pace: " + userPace, userSlider.xpos + userSlider.swidth / 2, userSlider.ypos + userSlider.sheight + 15);
}

public void drawElevationChart() {
  stroke(0);
  fill(0);

  // axes
  line(chartOrigin.x, chartOrigin.y, chartOrigin.x + raceLengthInPixels, chartOrigin.y);
  line(chartOrigin.x, chartOrigin.y, chartOrigin.x, chartOrigin.y - 500 * chartScale);
  line(chartOrigin.x + raceLengthInPixels, chartOrigin.y, chartOrigin.x + raceLengthInPixels, chartOrigin.y - 500 * chartScale);
  
  stroke(0);
  drawMileMarks(chartOrigin.y - (int) (500 * chartScale), (int) (500 * chartScale), true);

  // y-axis labels
  fill(0);
  textAlign(RIGHT);
  text("0", chartOrigin.x - 3, chartOrigin.y + 10);
  text("500", chartOrigin.x - 5, chartOrigin.y + 4 - 500 * chartScale);
  
  // data
  noStroke();
  fill(GUI_BASE_COLOR, 128);
  int i = 0;
  while (i < elevations.length - 1) {
    quad(chartOrigin.x + elevations[i].x * mileLengthInPixels, chartOrigin.y - elevations[i].y * chartScale,
        chartOrigin.x + elevations[i + 1].x * mileLengthInPixels, chartOrigin.y - elevations[i + 1].y * chartScale, 
        chartOrigin.x + elevations[i + 1].x * mileLengthInPixels, chartOrigin.y,
        chartOrigin.x + elevations[i].x * mileLengthInPixels, chartOrigin.y);
    i++;
  }

  // chart title
  textFont(font12, 12);
  fill(0xff303030);
  textAlign(LEFT);
  text("Elevation vs. distance", chartOrigin.x + 6, chartOrigin.y - 6);
  
  return;
}

public String secondsToHHMMSS(int s) {
  String[] parts = new String[3];
  parts[0] = nf(s / (60 * 60), 2);
  parts[1] = nf((s / 60) % 60, 2);
  parts[2] = nf(s % 60, 2);
    
  return join(parts, ":");
}

public String secondsToMMSS(int s) {
  return nf((s / 60) % 60, 2) + ":" + nf(s % 60, 2);
}

public int stringToSeconds(String hhmmss) {
  String[] parts = hhmmss.split(":");
  int rv = 0;
  
  rv += (new Integer(parts[2])).intValue();
  rv += (new Integer(parts[1])).intValue() * 60;
  rv += (new Integer(parts[0])).intValue() * 60 * 60;
  
  return rv;
}

public void drawMileMarks(int y, int l, boolean includeLabels) {
  int x = 0;
  stroke(0xff000000, 64);
  strokeWeight(1);
  textAlign(CENTER);
  textFont(font12, 12);
  for (int i = 0; i < 26; i++) {
    x = startLineX + (int) (i * mileLengthInPixels);
    line(x, y, x, y + l);
    if (includeLabels && i > 0) {
      text(i, x, y + l + 15);
    }
  }
  line(startLineX + raceLengthInPixels, y, startLineX + raceLengthInPixels, y + l);
  if (includeLabels) {
    text("26.2", startLineX + raceLengthInPixels, y + l + 15);
  }

  // darken start and finish lines
  stroke(0, 128);
  line(startLineX, y, startLineX, y + l);
  line(startLineX + raceLengthInPixels, y, startLineX + raceLengthInPixels, y + l);
}

public void drawRoundedRect(int x, int y, int w, int h, int r) {
  rect(x, y + r, w, h - 2 * r);
  rect(x + r, y, w - 2 * r, h);
  ellipse(x + r, y + r, r * 2, r * 2);
  ellipse(x + r, y + h - r - 1, r * 2, r * 2);
  ellipse(x + w - r - 1, y + r, r * 2, r * 2);
  ellipse(x + w - r - 1, y + h - r - 1, r * 2, r * 2);
}
class HScrollBar
{
  int swidth, sheight;    // width and height of bar
  int xpos, ypos;         // x and y position of bar
  float spos, newspos;    // x position of slider
  int sposMin, sposMax;   // max and min values of slider
  int loose;              // how loose/heavy
  boolean over;           // is the mouse over the slider?
  boolean locked;
  float ratio;

  HScrollBar (int xp, int yp, float initPercent) {
    swidth = 183;
    sheight = 20;
    int widthtoheight = swidth - sheight;
    ratio = (float)swidth / (float)widthtoheight;
    xpos = xp;
    ypos = yp-sheight/2;
    spos = xpos + swidth * initPercent - sheight/2;
    newspos = spos;
    sposMin = xpos;
    sposMax = xpos + swidth - sheight;
    loose = 5;
  }

  public void update() {
    if(over()) {
      over = true;
    } else {
      over = false;
    }
    if(mousePressed && over) {
      locked = true;
    }
    if(!mousePressed) {
      locked = false;
    }
    if(locked) {
      newspos = constrain(mouseX-sheight/2, sposMin, sposMax);
    }
    if(abs(newspos - spos) > 1) {
      spos = spos + (newspos-spos)/loose;
    }
  }

  public int constrain(int val, int minv, int maxv) {
    return min(max(val, minv), maxv);
  }

  public boolean over() {
    if(mouseX > xpos && mouseX < xpos+swidth &&
    mouseY > ypos && mouseY < ypos+sheight) {
      return true;
    } else {
      return false;
    }
  }

  public void display() {
    noStroke();
    fill(GUI_BASE_COLOR);
    rect(xpos, ypos - 1, swidth, sheight + 2);
    if(over || locked) {
      fill(GUI_THIRD_COLOR);
    } else {
      fill(GUI_HIGHLIGHT_COLOR);
    }
    rect(spos, ypos, sheight, sheight);
  }

  public float getPos() {
    // Convert spos to be values between
    // 0 and the total width of the scrollbar
    return spos * ratio;
  }
  
  public float getPercent() {
    // Convert spos to a value between 0 and 1
    return ((float) (spos - sposMin)) / (sposMax - sposMin);
  }
}
class Person {
  public int timeToFinish;       // in seconds
  public float currentMile;
  public int rank;
  
  public Person(String time, int overallRank) {
    rank = overallRank;
    timeToFinish = stringToSeconds(time);
    this.reset();
  }
  
  public void reset() {
    this.currentMile = 0 - this.wave() / mileLengthInPixels;
  }
  
  public void updatePace(int secondsPerMile) {
    this.timeToFinish = (int) (26.2f * secondsPerMile);
    this.timeToFinish = (int) Math.min(this.timeToFinish, 26.2f * 1200);
    this.timeToFinish = (int) Math.max(this.timeToFinish, 26.2f * 270);
  }
  
  public int wave() {
    return rank / 500;
  }
  
  // returns true if person crosses the finish line
  public boolean run(int seconds) {
    float oldMile = this.currentMile;
    
    float elevationFactor = this.hillFactor();
    currentMile += 26.2f * seconds * elevationFactor / timeToFinish;
    currentMile = Math.min(currentMile, 26.2f + this.wave() / mileLengthInPixels);
    
    return (currentMile >= 26.2f && oldMile < 26.2f);
  }
  
  private float hillFactor() {
    float rv = 1;
    
    int startIndex = elevations.length - 1;
    while (startIndex >= 0 && elevations[startIndex].x > this.currentMile) {
      startIndex--;
    }
    
    if (startIndex > 0 && startIndex < elevations.length - 1) {
      int secondsPerMile = (int) (this.timeToFinish / 26.2f);
      float deltaMiles = elevations[startIndex + 1].x - elevations[startIndex].x;
      float deltaFeet = elevations[startIndex + 1].y - elevations[startIndex].y;

      rv = 1 - 10 * (deltaFeet / milesToFeet(deltaMiles));
    }
    
    return rv;
  }
  
  private float milesToFeet(float miles) {
    return (miles * 5280);
  }
}

public Person[] generatePeople() {
  //String[] lines = loadStrings("/Users/jenny/Documents/Processing/sketch_apr01a/data/shuffled.txt");
  String[] lines = loadStrings("shuffled.txt");
  Person[] rv = new Person[lines.length];
  for (int i = 0; i < lines.length; i++) {
    String[] fields = split(lines[i], TAB);
    rv[i] = new Person(fields[1], (new Integer(fields[0])).intValue());
  }

  return rv;
}
class PointFloat {
  public float x;
  public float y;
  
  public PointFloat(float floatX, float floatY) {
    x = floatX;
    y = floatY;
  }
}

public PointFloat[] generateElevations() {
  // x in miles, y in feet
  PointFloat[] rv = {new PointFloat(0, 450), 
                new PointFloat(0.6f, 320), 
                new PointFloat(0.9f, 350), 
                new PointFloat(2.1f, 290), 
                new PointFloat(2.4f, 320), 
                new PointFloat(3.5f, 190), 
                new PointFloat(4.2f, 180), 
                new PointFloat(4.5f, 210), 
                new PointFloat(6.25f, 160), 
                new PointFloat(9.25f, 160), 
                new PointFloat(11.25f, 200), 
                new PointFloat(12, 140), 
                new PointFloat(12.3f, 160), 
                new PointFloat(12.75f, 140), 
                new PointFloat(14, 140), 
                new PointFloat(15.5f, 160), 
                new PointFloat(16, 50), 
                new PointFloat(16.5f, 125), 
                new PointFloat(17.5f, 125), 
                new PointFloat(17.75f, 160), 
                new PointFloat(19.25f, 100), 
                new PointFloat(19.4f, 150), 
                new PointFloat(20.25f, 150), 
                new PointFloat(20.75f, 235), 
                new PointFloat(21.5f, 150), 
                new PointFloat(22.2f, 150), 
                new PointFloat(22.5f, 100), 
                new PointFloat(23.5f, 80), 
                new PointFloat(24.2f, 20), 
                new PointFloat(26.2f, 20)};
  return rv;
}
class Button
{  
  int x, y;
  int size;
  int basecolor, highlightcolor;
  int currentcolor;
  boolean over = false;
  boolean pressed = false;   

  public void update() 
  {
    if(over()) {
      currentcolor = highlightcolor;
    } 
    else {
      currentcolor = basecolor;
    }
  }

  public boolean pressed() 
  {
    if(over) {
      locked = true;
      return true;
    } 
    else {
      locked = false;
      return false;
    }    
  }

  public boolean over() 
  { 
    return true; 
  }

  public boolean overRect(int x, int y, int width, int height) 
  {
    if (mouseX >= x && mouseX <= x+width && 
      mouseY >= y && mouseY <= y+height) {
      return true;
    } 
    else {
      return false;
    }
  }

  public boolean overCircle(int x, int y, int diameter) 
  {
    float disX = x - mouseX;
    float disY = y - mouseY;
    if(sqrt(sq(disX) + sq(disY)) < diameter/2 ) {
      return true;
    } 
    else {
      return false;
    }
  }

}

static int SQUARE_BUTTON_TYPE_PLAY = 0;
static int SQUARE_BUTTON_TYPE_PAUSE = 1;
static int SQUARE_BUTTON_TYPE_RESET = 2;
  
class SquareButton extends Button
{
  public int type;
  
  SquareButton(int ix, int iy, int itype) 
  {
    x = ix;
    y = iy;
    size = 50;
    type = itype;
    basecolor = GUI_BASE_COLOR;
    highlightcolor = GUI_HIGHLIGHT_COLOR;
    currentcolor = basecolor;
  }

  public boolean over() 
  {
    if( overRect(x, y, size, size) ) {
      over = true;
      return true;
    } 
    else {
      over = false;
      return false;
    }
  }

  public void display() 
  {
    noStroke();
    fill(GUI_BASE_COLOR);
    //rect(x, y, size, size);
    drawRoundedRect(x, y, size, size, 8);
    
    strokeJoin(ROUND);
    strokeWeight(7);
    if (over()) {
      fill(GUI_THIRD_COLOR);
      stroke(GUI_THIRD_COLOR);
    }
    else {
      fill(GUI_HIGHLIGHT_COLOR);
      stroke(GUI_HIGHLIGHT_COLOR);
    }
    
    if (this.type == SQUARE_BUTTON_TYPE_PLAY) {
      triangle(x + 17, y + 15, x + 33, y + size / 2, x + 17, y + size - 15 - 1);
    }
    else if (this.type == SQUARE_BUTTON_TYPE_PAUSE) {
      line(x + 17, y + 15, x + 17, y + size - 15 - 1);
      line(x + 33, y + 15, x + 33, y + size - 15 - 1);
    }
    else if (this.type == SQUARE_BUTTON_TYPE_RESET) {
      line(x + 17, y + 15, x + 17, y + size - 15 - 1);
      triangle(x + 33, y + 15, x + 17 + 4, y + size / 2, x + 33, y + size - 15 - 1);
    }
  }
}

  static public void main(String args[]) {
    PApplet.main(new String[] { "--present", "--bgcolor=#666666", "--hide-stop", "sketch_apr01a" });
  }
}
