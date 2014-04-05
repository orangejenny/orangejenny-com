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
    this.timeToFinish = (int) (26.2 * secondsPerMile);
    this.timeToFinish = (int) Math.min(this.timeToFinish, 26.2 * 1200);
    this.timeToFinish = (int) Math.max(this.timeToFinish, 26.2 * 270);
  }
  
  public int wave() {
    return rank / 500;
  }
  
  // returns true if person crosses the finish line
  public boolean run(int seconds) {
    float oldMile = this.currentMile;
    
    float elevationFactor = this.hillFactor();
    currentMile += 26.2 * seconds * elevationFactor / timeToFinish;
    currentMile = Math.min(currentMile, 26.2 + this.wave() / mileLengthInPixels);
    
    return (currentMile >= 26.2 && oldMile < 26.2);
  }
  
  private float hillFactor() {
    float rv = 1;
    
    int startIndex = elevations.length - 1;
    while (startIndex >= 0 && elevations[startIndex].x > this.currentMile) {
      startIndex--;
    }
    
    if (startIndex > 0 && startIndex < elevations.length - 1) {
      int secondsPerMile = (int) (this.timeToFinish / 26.2);
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

Person[] generatePeople() {
  //String[] lines = loadStrings("/Users/jenny/Documents/Processing/sketch_apr01a/data/shuffled.txt");
  String[] lines = loadStrings("shuffled.txt");
  Person[] rv = new Person[lines.length];
  for (int i = 0; i < lines.length; i++) {
    String[] fields = split(lines[i], TAB);
    rv[i] = new Person(fields[1], (new Integer(fields[0])).intValue());
  }

  return rv;
}
