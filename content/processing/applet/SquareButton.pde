class Button
{  
  int x, y;
  int size;
  color basecolor, highlightcolor;
  color currentcolor;
  boolean over = false;
  boolean pressed = false;   

  void update() 
  {
    if(over()) {
      currentcolor = highlightcolor;
    } 
    else {
      currentcolor = basecolor;
    }
  }

  boolean pressed() 
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

  boolean over() 
  { 
    return true; 
  }

  boolean overRect(int x, int y, int width, int height) 
  {
    if (mouseX >= x && mouseX <= x+width && 
      mouseY >= y && mouseY <= y+height) {
      return true;
    } 
    else {
      return false;
    }
  }

  boolean overCircle(int x, int y, int diameter) 
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

  boolean over() 
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

  void display() 
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
