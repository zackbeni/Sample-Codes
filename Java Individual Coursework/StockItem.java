/* 
The program below creates 4 stock instances of the 4 subclasses of StockItem superclass,
displays their stock information through a loop and allow update operations on their 
data such as increase and decrease stock as well as change the price of the item instance.
@Isaac Alliance Beni Nzayisenga Dushime, StudentID: 19000214
*/

//import the Scanner class from the "util" java package
import java.util.Scanner;

//import the InputMismatchException class from the "util" java package
import java.util.InputMismatchException;

//main (parent) class
public class StockItem
{
    //data fields declaration
    private int quantity;
    private double price;
    private String stockCode;
    private double VAT = 17.5; //initialise the VAT date field
    private double priceWithVAT;
    
    //no-arg constructor
    public StockItem(){}

    //argument constructor
    public StockItem(int quantity, double price, String stockCode)
    {
        this.quantity = quantity;
        this.price = price;
        this.stockCode = stockCode;
    }

    //get the stock Name
    public String getStockName()
    {
        return "Unknown Stock Name";
    }

    //get the stock description
    public String getStockDescription()
    {
        return "Unknown Stock Description";
    }
    
    //getter for quantity
    public int getQuantity()
    {
        return quantity;
    }

    //setter for quantity
    public void setQuantity(int quantity)
    {
        this.quantity = quantity;
    }

    //getter for stockCode
    public String getStockCode()
    {
        return stockCode;
    }

    //setter for stockCode
    public void setStockCode(String stockCode)
    {
        this.stockCode = stockCode;
    }

    //get The VAT rate
    public double getVAT()
    {
        return VAT;
    }

    //getter for price without VAT
    public double getPriceWithoutVAT()
    {
        return price;
    }

    //setter for price without VAT
    public void setPriceWithoutVAT(double price)
    {
        //ensure price is above zero
        if (price > 0)
        {
            System.out.println("Set new price to "+
            price + " per unit" + "\n");
            this.price = price;
        }

        //when input price is 0
        else if (price == 0)
        {
            System.out.println("Price must not be 0!\n"); 
        }

        //when input price is negative
        else
        {
            System.out.println("Price must not be negative!\n");   
        }
    }

    //getter for price with VAT
    public double getPriceWithVAT()
    {
        priceWithVAT = getPriceWithoutVAT() + (getPriceWithoutVAT() * (getVAT()/100));
        return priceWithVAT;
    }

    //create method to increase stock
    public void addStock(int stockQuantityIncrease)
    {
        System.out.println("Increase stock by "+
        stockQuantityIncrease + " units" + "\n");

        //increase quantity validation
        if(stockQuantityIncrease >= 1 
        && ((stockQuantityIncrease + getQuantity()) <= 100))
        {
            this.quantity += stockQuantityIncrease;
        }
        
        //when increase quantity is 0
        else if(stockQuantityIncrease == 0)
        {
            System.out.println("Error: Increase quantity must not be 0!\n");
        }

        //when increase quantity is negative
        else if(stockQuantityIncrease < 0)
        {
            System.out.println("Error: Increase quantity must not be negative!\n");
        }

        //when increase quantity leads to stock exceeding its maximum quantity:100
        else 
        {
            System.out.println("Error: Increase quantity "+ stockQuantityIncrease+
            " will make the stock exceed its maximum:100!\n");
        }
    }

    //method to sell stock items 
    public boolean sellStock(int stockQuantityDecrease)
    {
        //when stock quantity is less than 1
        if (stockQuantityDecrease < 1)
        {
            System.out.println("Reduce stock by "+
            stockQuantityDecrease + " units" + "\n");
        
            //display an error if quantity increase is out of bounds
            System.out.print("Error: Decrease quantity must not be 0 and must be greater "+
            "than 0\n");

            return false;
        }

        //when a valid stock decrease value is passed
        else if(stockQuantityDecrease >= 1 && stockQuantityDecrease <= getQuantity() )
        {
            System.out.println("Reduce stock by "+
            stockQuantityDecrease + " units");
    
            this.quantity -= stockQuantityDecrease;
            return true;             
        }

        //when decrease quantity is beyond the current stock quantity
        else
        {
            System.out.println("Reduce stock by "+
            stockQuantityDecrease + " units" + "\n");
    
            return false;   
        }  
    }

    //return a string representation of the object/instance
    @Override
    public String toString()
    {
        return "Printing item stock information:" + "\n"+
        "Stock Type: " + getStockName() +"\nDescription: " + getStockDescription()+
        "\nStock Code: " + getStockCode() +"\n"+
        "PriceWithoutVAT:" + getPriceWithoutVAT() +"\n"+
        "PriceWithVAT:" + getPriceWithVAT()+ 
        "\t(per rate of " + getVAT() + ")"+
        "\nTotal units in stock: " + getQuantity() + "\n";
    }
}

//Car Navigation system class
class NavSys extends StockItem
{
    //no-arg constructor
    public NavSys(){}

    //argument constructor
    public NavSys(int quantity, double price, String stockCode)
    {
        super(quantity, price, stockCode);
    }

    //get the stock Name
    @Override
    public String getStockName()
    {
        return "Navigation system";
    }

    //get the stock description
    @Override
    public String getStockDescription()
    {
        return "GeoVision Sat Nav";
    }

    //return a string representation of the object/instance
    @Override
    public String toString()
    {
        return "Printing item stock information:" + "\n"+
        "Stock Type: " + getStockName() +"\nDescription: " + getStockDescription()+
        "\nStock Code: " + super.getStockCode() +"\n"+
        "PriceWithoutVAT:" + super.getPriceWithoutVAT() +"\n"+
        "PriceWithVAT:" + super.getPriceWithVAT()+
        "\t(per rate of " + super.getVAT() + ")"+
        "\nTotal units in stock: " + super.getQuantity() + "\n";
    }    
}

// Car Radio System class
class RadioSys extends StockItem
{
    //no-arg constructor
    public RadioSys(){}

    //argument constructor
    public RadioSys(int quantity, double price, String stockCode)
    {
        super(quantity, price, stockCode);
    }

    //get the stock Name
    @Override
    public String getStockName()
    {
        return "Radio system";
    }

    //get the stock description
    @Override
    public String getStockDescription()
    {
        return "Car HI-FI system";
    }

    //return a string representation of the object/instance
    @Override
    public String toString()
    {
        return "Printing item stock information:" + "\n"+
        "Stock Type: " + getStockName() +"\nDescription: " + getStockDescription()+
        "\nStock Code: " + super.getStockCode() +"\n"+
        "PriceWithoutVAT:" + super.getPriceWithoutVAT() +"\n"+
        "PriceWithVAT:" + super.getPriceWithVAT()+
        "\t(per rate of " + super.getVAT() + ")" +
        "\nTotal units in stock: " + super.getQuantity() + "\n";
    }   
}

// Car Alarm System class
class AlarmSys extends StockItem
{
    //no-arg constructor
    public AlarmSys(){}

    //argument constructor
    public AlarmSys(int quantity, double price, String stockCode)
    {
        super(quantity, price, stockCode);
    }

    //get the stock Name
    @Override
    public String getStockName()
    {
        return "Alarm system";
    }

    //get the stock description
    @Override
    public String getStockDescription()
    {
        return "Car security alarm system";
    }

    //return a string representation of the object/instance
    @Override
    public String toString()
    {
        return "Printing item stock information:" + "\n"+
        "Stock Type: " + getStockName() +"\nDescription: " + getStockDescription()+
        "\nStock Code: " + super.getStockCode() +"\n"+
        "PriceWithoutVAT:" + super.getPriceWithoutVAT() +"\n"+
        "PriceWithVAT:" + super.getPriceWithVAT()+
        "\t(per rate of " + super.getVAT() + ")" +
        "\nTotal units in stock: " + super.getQuantity() + "\n";
    }   
}

// Car Light sensor System class
class LightSensor extends StockItem
{
    //no-arg constructor
    public LightSensor(){}

    //argument constructor
    public LightSensor(int quantity, double price, String stockCode)
    {
        super(quantity, price, stockCode);
    }

    //get the stock Name
    @Override
    public String getStockName()
    {
        return "Light Detection system";
    }

    //get the stock description
    @Override
    public String getStockDescription()
    {
        return "Car LIDAR Sytem"; // LIDAR in full: Light detection and Ranging
    }

    //return a string representation of the object/instance
    @Override
    public String toString()
    {
        return "Printing item stock information:" + "\n"+
        "Stock Type: " + getStockName() +"\nDescription: " + getStockDescription()+
        "\nStock Code: " + super.getStockCode() +"\n"+
        "PriceWithoutVAT:" + super.getPriceWithoutVAT() +"\n"+
        "PriceWithVAT:" + super.getPriceWithVAT()+
        "\t(per rate of " + super.getVAT() + ")" +
        "\nTotal units in stock: " + super.getQuantity() + "\n";
    }  
}

//create a test class called TestPolymorphism
class TestPolymorphism
{
     
    //method to increase stock, decrease stock or set a new price
    public static void itemInstance(StockItem item)
    {
        //create a Scanner object
        Scanner input = new Scanner(System.in);

        //declare and initialise variable "check"
        int check = 0;

        //declare and initialise variable "readStockUpdate"
        int readStockUpdate = 0;

        //declare and initialise varible "readNewPrice"
        Double readNewPrice = 0.0;

        //declare and initialise variable "stopLoop" to false to enter the loop
        boolean stopLoop = false; 

        //create a user interface to choose an operation
        while(!stopLoop)
        {
            //prompt the user to choose which operation they want to do
            System.out.println("\nEnter: \n1 to increase stock \n2 to decrease stock"+
            "\n3 to set new price" +
            "\npress any other number to continue");

            /*use try and catch statements to ensure there are no input errors, 
            throw an exception and ensure program execution continuation*/
            try
            {
                check = input.nextInt();
                stopLoop = true; //assign true to stopLoop to end looping
            }

            catch(InputMismatchException e)
            {
                System.out.println("Wrong input! Input must be an integer\n");
                input.nextLine();
            }
        }

        //validate whether the value in variable "check" is between [1, 3]
        if(check >= 1 && check <= 3)
        {
            //create a while loop to facilitate repeating a certain operation
            while(check >= 1 && check <= 3)
            {
                /*create a switch statement with increase stock, reduce stock
                and set price cases*/
                switch(check)
                {
                    //increase stock
                    case 1:
                    stopLoop = false; //assign false to stopLoop to enter the loop

                    while(!stopLoop)
                    {
                        //prompt the user to input the increase units
                        System.out.print("Enter the increase units: ");

                        /*use try and catch statements to ensure there are 
                        no input errors, throw an exception and ensure program
                        execution continuation*/
                        try
                        {
                            readStockUpdate = input.nextInt();
                            stopLoop = true; //assign false to stopLoop to enter the loop
                        }

                        catch(InputMismatchException e)
                        {
                            System.out.println("Wrong input! Input must be an integer\n");
                            input.nextLine();
                        }
                    }

                    //call addStock() method to increase stock
                    item.addStock(readStockUpdate);

                    //display item stock information
                    System.out.print(item);
                    break;

                    //reduce stock
                    case 2:
                    stopLoop = false; //assign false to stopLoop to enter the loop
                    
                    while(!stopLoop)
                    {
                        //prompt the user to input the decrease units
                        System.out.print("Enter the decrease units: ");
                        try
                        {
                            readStockUpdate = input.nextInt();
                            stopLoop = true; //assign true to stopLoop to end looping
                        }

                        catch(InputMismatchException e)
                        {
                            System.out.println("Wrong input! Input must be an integer\n");
                            input.nextLine();
                        }
                    }

                    //call sellStock() method and pass readStockUpdate to reduce stock
                    System.out.print(item.sellStock(readStockUpdate));

                    //display item stock information
                    System.out.print("\n\n" +item);
                    break;

                    //set price
                    case 3:
                    stopLoop = false; //assign false to stopLoop to enter the loop
                    
                    while(!stopLoop)
                    {
                        //prompt the user to input the new price
                        System.out.print("Enter the new price: ");

                         /*use try and catch statements to ensure there are 
                        no input errors, throw an exception and ensure program
                        execution continuation*/
                        try
                        {
                            readNewPrice = input.nextDouble();
                            stopLoop = true; //assign true to stopLoop to end looping
                        }

                        catch(InputMismatchException e)
                        {
                            System.out.println("Wrong input! Input must be a real number\n");
                            input.nextLine();
                        }
                    }
                    

                    //call setPricewithoutVAT() and pass readNewPrice to set a new price
                    item.setPriceWithoutVAT(readNewPrice);

                    //display item stock information
                    System.out.print(item);
                    break;
                }

                System.out.print("\nWould like to do another operation?\n");
                
                stopLoop = false; //assign false to stopLoop to enter the loop

                //create a user interface to repeat an operation
                while(!stopLoop)
                {
                    System.out.println("\nEnter \n1 to increase stock" +
                    "\n2 to decrease stock" +
                    "\n3 to set new price"+
                    "\nany other number to continue");
    
                    /*use try and catch statements to ensure there are 
                    no input errors, throw an exception and ensure program
                    execution continuation*/
                    try
                    {
                        check = input.nextInt();
                        stopLoop = true; //assign true to stopLoop to end looping
                    }

                    catch(InputMismatchException e)
                    {
                        System.out.println("Wrong input! Input must be an integer\n");
                        input.nextLine();
                    }
                }
                
                //when user chooses to perform another operation
                if(check >= 1 && check <= 3)
                {
                    System.out.println("Carrying out chosen operation!\n");
                }

                //when user chooses to contine with the rest of the program
                else
                {
                    //display a message to notify the user that the loop is ending
                    System.out.println("Ending loop!\n");
                    break;   
                }
            }
        }
    }
   
    //method to validate and return the initial stock quantity
    public static int checkInitialQuantity()
    {
        //create a Scanner object
        Scanner input = new Scanner(System.in);
        
        //declare and initialise variable "quantity" 
        int quantity = 0;

        //declare and initialise varibale "stopLoop" to false to enter the loop
        boolean stopLoop = false;

        //create a user interface to perform quantity initialisation
        while(!stopLoop)
        {
            /*use try and catch statements to ensure there are 
            no input errors, throw an exception and ensure program
            execution continuation*/
            try
            {
                //prompt the user to enter the initial quantity
                System.out.print("Enter the initial stock quantity: ");
                quantity = input.nextInt();

                //check whether quantity input is valid i.e. between ]0,100]
                if(quantity > 0 && quantity <= 100)
                {
                    stopLoop = true; //assign true to stopLoop to end the loop
                    System.out.println("validation complete and input is valid\n");   
                }

                //when the input quantity is 0
                if(quantity == 0)
                {
                    stopLoop = false; //assign false to stopLoop to keep looping
                    System.out.println("Error: Initial quantity must not be 0!\n");
                }

                //when the input quantity is negative
                if(quantity < 0)
                {
                    stopLoop = false; //assign false to stopLoop to keep looping
                    System.out.println("Error: Initial quantity must not be negative!\n");
                }

                //when the input quantity is beyond maximum stock: 100
                if (quantity > 100)
                {
                    stopLoop = false; //assign false to stopLoop to keep looping
                    System.out.println("Error: Initial quantity must not exceed 100\n"); 
                }
            }

            catch(InputMismatchException e)
            {
                System.out.println("Wrong input! Input must be an integer\n");
                input.nextLine();
            }    
        }

        //return the value stored in variable "quantity"
        return quantity;  
    }

    //method to validate and return the initial price of a stock item
    public static double checkInitialPrice()
    {
        //create a Scanner object
        Scanner input = new Scanner(System.in);

        //declare and initialise variable "price"
        double price = 0;

        //declare and initialise variable "stopLoop" to false to enter loop
        boolean stopLoop = false; //assign false to stopLoop to enter the loop

        //create a user interface to perform price initialisation
        while(!stopLoop)
        {
            /*use try and catch statements to ensure there are 
            no input errors, throw an exception and ensure program
            execution continuation*/
            try
            {
                System.out.println("Enter the initial price:");
                price = input.nextDouble();

                //check whether the input price is valid i.e. not negative
                if (price > 0)
                {
                    stopLoop = true; //assign false to stopLoop to end the loop
                    System.out.println("Validation complete and input is valid\n");
                }

                //when input price is 0
                else if(price == 0)
                {
                    stopLoop = false; //assign false to stopLoop to keep looping
                    System.out.println("Price must not be 0!\n"); 
                }

                //when input price is negative
                else
                {
                    stopLoop = false; //assign false to stopLoop to keep looping
                    System.out.println("Price must not be negative!\n");   
                }
            }

            catch(InputMismatchException e)
            {
                System.out.println("wrong input! Input must be a real number\n");
                input.nextLine();
            }
        }
        
        //return the value stored in "price"
        return price;    
    }

    //method to validate and return the stock code
    public static String checkCode()
    {
        //create a Scanner object
        Scanner input = new Scanner(System.in);

        //declare and initialise variable "stockCode"
        String stockCode = null;

        //declare and initialise variable "stopLoop" to false to enter the loop
        boolean stopLoop = false; //assign false to stopLoop to enter the loop

        //create a user interface to enter item the stock code
        while(!stopLoop)
        {
            /*use try and catch statements to ensure there are 
            no input errors, throw an exception and ensure program
            execution continuation*/
            try
            {
                System.out.println("Enter the stock code:");
                stockCode = input.next();
                stopLoop = true; //assign false to stopLoop to end the loop
            }

            catch(InputMismatchException e)
            {
                System.out.println("wrong input! Stock Code must be a string\n");
                input.nextLine();
            }
        }

        //return the String value stored in "stockCode"
        return stockCode;
    }
    
    
    //main method
    public static void main(String[] args)
    {
        //declare a StockItem array of 4 elements
        StockItem[] s = new StockItem[4];
        
        //declare initial quantity, initial price and stock Code variables
        int initialQuantity;
        double initialPrice;
        String stockCode;
        
        //display a message of what entries are going to be collected
        System.out.println("\t\t\tEntries for Navigation System");

        //invoke checkInitialQuantity() and assign its return value to initialQuantity
        initialQuantity = checkInitialQuantity();

        //invoke checkInitialInitialPrice() and assign its return value to initialPrice
        initialPrice = checkInitialPrice();

        //invoke checkCode() and assign its return value to stockCode
        stockCode = checkCode();

        /*create a navigation system object and 
        use s[o] as its object reference variable */
        s[0] = new NavSys(initialQuantity, initialPrice, stockCode);

        //display a message of the what entries are going to be collected
        System.out.println("\t\t\tEntries for Radio System");

        //invoke checkInitialQuantity() and assign its return value to initialQuantity
        initialQuantity = checkInitialQuantity();

        //invoke checkInitialInitialPrice() and assign its return value to initialPrice
        initialPrice = checkInitialPrice();

        //invoke checkCode() and assign its return value to stockCode
        stockCode = checkCode();

        /*create a  radio system objec and 
        use s[1] as its object reference variable */
        s[1] = new RadioSys(initialQuantity, initialPrice, stockCode);

        //display a message of the what entries are going to be collected
        System.out.println("\t\t\tEntries for Alarm system");

        //invoke checkInitialQuantity() and assign its return value to initialQuantity
        initialQuantity = checkInitialQuantity();

        //invoke checkInitialInitialPrice() and assign its return value to initialPrice
        initialPrice = checkInitialPrice();

        //invoke checkCode() and assign its return value to stockCode
        stockCode = checkCode();
        
        /*create a alarm system object and 
        use s[2] as its object reference variable*/
        s[2] = new AlarmSys(initialQuantity, initialPrice, stockCode);

        //display a message of the what entries are going to be collected
        System.out.println("\t\t\tEntries for Light Detection system");

        //invoke checkInitialQuantity() and assign its return value to initialQuantity
        initialQuantity = checkInitialQuantity();

        //invoke checkInitialInitialPrice() and assign its return value to initialPrice
        initialPrice = checkInitialPrice();
        
        //invoke checkCode() and assign its return value to stockCode
        stockCode = checkCode();
       
        /*create a light sensor system object and 
        use s[3] as its object reference variable*/
        s[3] = new LightSensor(initialQuantity, initialPrice, stockCode);

        //Create a for loop to test each created subclass
        for (int i = 0; i < s.length; i++)
        {
            //display header for object or instance created
            System.out.print("\nCreating a stock of "+
            s[i].getQuantity() +" units " + s[i].getStockName() +"," +
            " price " + s[i].getPriceWithoutVAT() + " each " +
            "and item code " + s[i].getStockCode() + "\n\n");

            //display stock information
            System.out.print(s[i]);

            //call the instance method and pass an object reference variable
            itemInstance(s[i]);  
        }
        
        //end of code
        System.out.print("End of code!");
    }  
}  